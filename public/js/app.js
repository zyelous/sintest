/**
 * ==========================================================================
 * SINTARA — Sistem Informasi Tata Naskah dan Arsip
 * Main Application JavaScript
 * Bappeda Provinsi Lampung
 * ==========================================================================
 */

document.addEventListener('DOMContentLoaded', function () {

    /* -----------------------------------------------------------------------
     * 1. SIDEBAR TOGGLE (Mobile)
     * ----------------------------------------------------------------------- */
    const sidebar       = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const hamburgerBtn  = document.querySelector('.hamburger');

    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.add('open');
        if (sidebarOverlay) sidebarOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('open');
        if (sidebarOverlay) sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (hamburgerBtn) {
        hamburgerBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (sidebar && sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    // Close sidebar on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeSidebar();
            closeAllModals();
            closeAllDropdowns();
        }
    });


    /* -----------------------------------------------------------------------
     * 2. SIDEBAR ACTIVE STATE (based on current URL)
     * ----------------------------------------------------------------------- */
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    const currentPath  = window.location.pathname;

    sidebarLinks.forEach(function (link) {
        const href = link.getAttribute('href');
        if (!href || href === '#') return;

        try {
            const linkPath = new URL(href, window.location.origin).pathname;

            // Exact match for dashboard ('/'), prefix match for others
            if (linkPath === '/' || linkPath === '/dashboard') {
                if (currentPath === '/' || currentPath === '/dashboard') {
                    link.classList.add('active');
                }
            } else if (currentPath.startsWith(linkPath)) {
                link.classList.add('active');
            }
        } catch (_) {
            // Ignore invalid URLs
        }
    });


    /* -----------------------------------------------------------------------
     * 3. MODAL SYSTEM
     *
     * Open:  data-modal-target="#modal-id"
     * Close: data-modal-close  (or click overlay)
     * ----------------------------------------------------------------------- */
    function openModal(modalId) {
        const modal = document.querySelector(modalId);
        if (!modal) return;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modal) {
        if (!modal) return;
        modal.classList.remove('active');
        // Restore scroll only if no other modals are open
        const anyOpen = document.querySelector('.modal-overlay.active');
        if (!anyOpen) {
            document.body.style.overflow = '';
        }
    }

    function closeAllModals() {
        document.querySelectorAll('.modal-overlay.active').forEach(function (m) {
            closeModal(m);
        });
    }

    // Open triggers
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-modal-target]');
        if (trigger) {
            e.preventDefault();
            const target = trigger.getAttribute('data-modal-target');
            openModal(target);
        }
    });

    // Close triggers
    document.addEventListener('click', function (e) {
        const closeBtn = e.target.closest('[data-modal-close]');
        if (closeBtn) {
            e.preventDefault();
            const modal = closeBtn.closest('.modal-overlay');
            closeModal(modal);
        }
    });

    // Close on overlay click (not card)
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('modal-overlay') && e.target.classList.contains('active')) {
            closeModal(e.target);
        }
    });

    // Expose for external use
    window.SINTARA = window.SINTARA || {};
    window.SINTARA.openModal  = openModal;
    window.SINTARA.closeModal = closeModal;


    /* -----------------------------------------------------------------------
     * 4. DELETE CONFIRMATION MODAL
     *
     * Usage:
     *   <button data-delete-url="/resource/1"
     *           data-delete-name="Surat ABC">
     *     Hapus
     *   </button>
     *
     * Requires a #delete-modal in the DOM (from delete-modal component).
     * ----------------------------------------------------------------------- */
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-delete-url]');
        if (!trigger) return;

        e.preventDefault();

        const url  = trigger.getAttribute('data-delete-url');
        const name = trigger.getAttribute('data-delete-name') || '';

        const deleteForm = document.querySelector('#delete-form');
        const deleteNameEl = document.querySelector('#delete-item-name');

        if (deleteForm) {
            deleteForm.setAttribute('action', url);
        }

        if (deleteNameEl) {
            deleteNameEl.textContent = name;
        }

        openModal('#delete-modal');
    });


    /* -----------------------------------------------------------------------
     * 5. FLASH MESSAGE AUTO-DISMISS
     * ----------------------------------------------------------------------- */
    const flashMessages = document.querySelectorAll('.alert[data-auto-dismiss]');

    flashMessages.forEach(function (alert) {
        const delay = parseInt(alert.getAttribute('data-auto-dismiss'), 10) || 5000;

        setTimeout(function () {
            alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';

            setTimeout(function () {
                alert.remove();
            }, 400);
        }, delay);
    });

    // Manual dismiss
    document.addEventListener('click', function (e) {
        const dismissBtn = e.target.closest('.alert-dismiss');
        if (!dismissBtn) return;

        const alert = dismissBtn.closest('.alert');
        if (alert) {
            alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';

            setTimeout(function () {
                alert.remove();
            }, 300);
        }
    });


    /* -----------------------------------------------------------------------
     * 6. FILE UPLOAD PREVIEW
     *
     * Usage:
     *   <div class="file-upload-area" data-file-upload>
     *     <input type="file" id="myFile" name="lampiran">
     *     ...
     *   </div>
     *   <div id="myFile-preview" class="file-upload-preview" style="display:none">
     *     <span class="file-name"></span>
     *     <span class="file-size"></span>
     *     <button type="button" class="file-remove">&times;</button>
     *   </div>
     * ----------------------------------------------------------------------- */
    const fileUploadAreas = document.querySelectorAll('.file-upload-area[data-file-upload]');

    fileUploadAreas.forEach(function (area) {
        const fileInput = area.querySelector('input[type="file"]');
        if (!fileInput) return;

        const previewId = fileInput.id + '-preview';
        const preview   = document.getElementById(previewId);

        // Click to open file dialog
        area.addEventListener('click', function (e) {
            if (e.target !== fileInput) {
                fileInput.click();
            }
        });

        // Drag & drop
        area.addEventListener('dragover', function (e) {
            e.preventDefault();
            area.classList.add('drag-over');
        });

        area.addEventListener('dragleave', function () {
            area.classList.remove('drag-over');
        });

        area.addEventListener('drop', function (e) {
            e.preventDefault();
            area.classList.remove('drag-over');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                updateFilePreview(fileInput, preview);
            }
        });

        // File selected
        fileInput.addEventListener('change', function () {
            updateFilePreview(fileInput, preview);
        });

        // Remove file
        if (preview) {
            const removeBtn = preview.querySelector('.file-remove');
            if (removeBtn) {
                removeBtn.addEventListener('click', function () {
                    fileInput.value = '';
                    preview.style.display = 'none';
                });
            }
        }
    });

    function updateFilePreview(input, preview) {
        if (!preview || !input.files || !input.files[0]) return;

        const file = input.files[0];
        const nameEl = preview.querySelector('.file-name');
        const sizeEl = preview.querySelector('.file-size');

        if (nameEl) nameEl.textContent = file.name;
        if (sizeEl) sizeEl.textContent = formatFileSize(file.size);

        preview.style.display = 'flex';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 B';
        const units = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        return parseFloat((bytes / Math.pow(1024, i)).toFixed(1)) + ' ' + units[i];
    }


    /* -----------------------------------------------------------------------
     * 7. SEARCH DEBOUNCE
     *
     * Usage:
     *   <input type="text" data-search-debounce data-search-form="#filter-form">
     *
     * Submits the parent or target form after 400ms of inactivity.
     * ----------------------------------------------------------------------- */
    const searchInputs = document.querySelectorAll('[data-search-debounce]');

    searchInputs.forEach(function (input) {
        let timer = null;
        const delay = parseInt(input.getAttribute('data-search-delay'), 10) || 400;

        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                const formSelector = input.getAttribute('data-search-form');
                const form = formSelector
                    ? document.querySelector(formSelector)
                    : input.closest('form');

                if (form) form.submit();
            }, delay);
        });
    });


    /* -----------------------------------------------------------------------
     * 8. DROPDOWN TOGGLE (User Menu & Generic)
     *
     * Usage:
     *   <div class="user-dropdown" data-dropdown>
     *     <button class="user-dropdown-toggle" data-dropdown-toggle> ... </button>
     *     <div class="user-dropdown-menu"> ... </div>
     *   </div>
     * ----------------------------------------------------------------------- */
    const dropdowns = document.querySelectorAll('[data-dropdown]');

    dropdowns.forEach(function (dropdown) {
        const toggle = dropdown.querySelector('[data-dropdown-toggle]');
        if (!toggle) return;

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();

            // Close other dropdowns first
            dropdowns.forEach(function (d) {
                if (d !== dropdown) d.classList.remove('open');
            });

            dropdown.classList.toggle('open');
        });
    });

    function closeAllDropdowns() {
        dropdowns.forEach(function (d) {
            d.classList.remove('open');
        });
    }

    // Close dropdowns on outside click
    document.addEventListener('click', function (e) {
        dropdowns.forEach(function (dropdown) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });
    });


    /* -----------------------------------------------------------------------
     * 9. CHART.JS INITIALIZATION HELPERS
     *
     * Provides convenience wrappers around Chart.js for the dashboard.
     *
     * Usage:
     *   SINTARA.createChart('myCanvasId', { ... Chart.js config ... });
     *   SINTARA.createBarChart('canvasId', labels, data, options);
     *   SINTARA.createDoughnutChart('canvasId', labels, data, colors);
     *   SINTARA.createLineChart('canvasId', labels, data, options);
     * ----------------------------------------------------------------------- */

    // Common default options matching SINTARA design tokens
    const chartDefaults = {
        fontFamily: "'Inter', sans-serif",
        colors: {
            primary:  '#1B3A5C',
            accent:   '#3B82F6',
            success:  '#10B981',
            warning:  '#F59E0B',
            danger:   '#EF4444',
            info:     '#06B6D4',
        },
        palette: ['#1B3A5C', '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#06B6D4', '#8B5CF6', '#EC4899'],
        gridColor: '#E2E8F0',
        textColor: '#64748B',
    };

    /**
     * Generic chart creator — thin wrapper around new Chart().
     */
    window.SINTARA.createChart = function (canvasId, config) {
        const canvas = document.getElementById(canvasId);
        if (!canvas || typeof Chart === 'undefined') return null;

        // Apply default font
        if (!config.options)          config.options = {};
        if (!config.options.plugins)  config.options.plugins = {};
        if (!config.options.plugins.legend) config.options.plugins.legend = {};

        config.options.plugins.legend.labels = Object.assign(
            { font: { family: chartDefaults.fontFamily, size: 12 } },
            config.options.plugins.legend.labels || {}
        );

        return new Chart(canvas.getContext('2d'), config);
    };

    /**
     * Bar chart shorthand.
     */
    window.SINTARA.createBarChart = function (canvasId, labels, data, options) {
        options = options || {};

        return window.SINTARA.createChart(canvasId, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: options.label || 'Data',
                    data: data,
                    backgroundColor: options.colors || chartDefaults.palette.map(function (c) { return c + 'CC'; }),
                    borderColor: options.colors || chartDefaults.palette,
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: options.aspectRatio !== false,
                plugins: {
                    legend: { display: !!options.showLegend },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        titleFont: { family: chartDefaults.fontFamily },
                        bodyFont:  { family: chartDefaults.fontFamily },
                        padding: 10,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: chartDefaults.gridColor },
                        ticks: {
                            font: { family: chartDefaults.fontFamily, size: 11 },
                            color: chartDefaults.textColor,
                        },
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: chartDefaults.fontFamily, size: 11 },
                            color: chartDefaults.textColor,
                        },
                    }
                }
            }
        });
    };

    /**
     * Doughnut / Pie chart shorthand.
     */
    window.SINTARA.createDoughnutChart = function (canvasId, labels, data, colors) {
        return window.SINTARA.createChart(canvasId, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors || chartDefaults.palette,
                    borderWidth: 2,
                    borderColor: '#FFFFFF',
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 16,
                            usePointStyle: true,
                            pointStyleWidth: 10,
                            font: { family: chartDefaults.fontFamily, size: 12 },
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        titleFont: { family: chartDefaults.fontFamily },
                        bodyFont:  { family: chartDefaults.fontFamily },
                        padding: 10,
                        cornerRadius: 8,
                    }
                }
            }
        });
    };

    /**
     * Line chart shorthand.
     */
    window.SINTARA.createLineChart = function (canvasId, labels, datasets, options) {
        options = options || {};

        // Normalize single dataset to array
        if (!Array.isArray(datasets)) {
            datasets = [datasets];
        }

        const formattedDatasets = datasets.map(function (ds, i) {
            const color = ds.color || chartDefaults.palette[i % chartDefaults.palette.length];
            return {
                label: ds.label || 'Dataset ' + (i + 1),
                data: ds.data,
                borderColor: color,
                backgroundColor: color + '1A', // 10% opacity
                fill: ds.fill !== undefined ? ds.fill : true,
                tension: 0.4,
                pointRadius: 3,
                pointHoverRadius: 6,
                pointBackgroundColor: color,
                pointBorderColor: '#FFFFFF',
                pointBorderWidth: 2,
                borderWidth: 2,
            };
        });

        return window.SINTARA.createChart(canvasId, {
            type: 'line',
            data: {
                labels: labels,
                datasets: formattedDatasets,
            },
            options: {
                responsive: true,
                maintainAspectRatio: options.aspectRatio !== false,
                plugins: {
                    legend: {
                        display: datasets.length > 1,
                        labels: {
                            usePointStyle: true,
                            font: { family: chartDefaults.fontFamily, size: 12 },
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        titleFont: { family: chartDefaults.fontFamily },
                        bodyFont:  { family: chartDefaults.fontFamily },
                        padding: 10,
                        cornerRadius: 8,
                        mode: 'index',
                        intersect: false,
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: chartDefaults.gridColor },
                        ticks: {
                            font: { family: chartDefaults.fontFamily, size: 11 },
                            color: chartDefaults.textColor,
                        },
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: chartDefaults.fontFamily, size: 11 },
                            color: chartDefaults.textColor,
                        },
                    }
                }
            }
        });
    };

});
