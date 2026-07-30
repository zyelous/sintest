<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label Arsip - {{ $arsip->no_berkas }}</title>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
            color: #0f172a;
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            background-color: #1b3a5c;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-print:hover {
            background-color: #0f2744;
        }
        .label-card {
            width: 140mm;
            min-height: 90mm;
            background: #ffffff;
            border: 2px solid #1b3a5c;
            border-radius: 8px;
            margin: 0 auto;
            padding: 12px;
            box-sizing: border-box;
            position: relative;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .label-header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #1b3a5c;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .label-header img {
            width: 45px;
            height: 45px;
            margin-right: 10px;
            object-fit: contain;
        }
        .label-header-title h1 {
            font-size: 14px;
            margin: 0;
            color: #1b3a5c;
            font-weight: 800;
            text-transform: uppercase;
        }
        .label-header-title p {
            font-size: 10px;
            margin: 2px 0 0 0;
            color: #475569;
            font-weight: 600;
        }
        .label-body {
            display: flex;
            gap: 12px;
        }
        .label-info {
            flex: 1;
        }
        .info-row {
            margin-bottom: 6px;
        }
        .info-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .info-val {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }
        .info-val-large {
            font-size: 15px;
            color: #1b3a5c;
        }
        .label-qr {
            width: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-left: 1px dashed #cbd5e1;
            padding-left: 10px;
        }
        .qr-code {
            width: 85px;
            height: 85px;
        }
        .qr-code img {
            width: 100% !important;
            height: 100% !important;
        }
        .qr-subtext {
            font-size: 8px;
            color: #64748b;
            margin-top: 4px;
            font-weight: bold;
        }
        .label-footer {
            margin-top: 10px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #64748b;
        }
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .label-card {
                box-shadow: none;
                margin: 0;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak Label Stiker Arsip</button>
    </div>

    <div class="label-card">
        <div class="label-header">
            <img src="{{ asset('images/logo_lampung.png') }}" alt="Logo Lampung">
            <div class="label-header-title">
                <h1>BAPPEDA PROVINSI LAMPUNG</h1>
                <p>LABEL LOKASI FISIK & KLASIFIKASI KEARSIPAN</p>
            </div>
        </div>

        <div class="label-body">
            <div class="label-info">
                <div class="info-row">
                    <div class="info-label">Nomor Berkas</div>
                    <div class="info-val info-val-large">{{ $arsip->no_berkas }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kode Klasifikasi Surat</div>
                    <div class="info-val">{{ $arsip->kode_klasifikasi }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Judul / Uraian Berkas</div>
                    <div class="info-val" style="font-size: 11px; line-height: 1.2;">{{ Str::limit($arsip->uraian_berkas, 90) }}</div>
                </div>
                <div class="info-row" style="display: flex; gap: 10px; margin-top: 6px;">
                    <div>
                        <div class="info-label">No. Rak</div>
                        <div class="info-val">{{ $arsip->no_rak ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="info-label">No. Boks</div>
                        <div class="info-val">{{ $arsip->no_boks ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="info-label">Folder</div>
                        <div class="info-val">{{ $arsip->no_folder ?: '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="label-qr">
                <div id="qrcode" class="qr-code"></div>
                <div class="qr-subtext">SINTARA DIGITAL</div>
            </div>
        </div>

        <div class="label-footer">
            <span>Unit Pengolah: <strong>{{ $arsip->bidang?->nama_bidang ?? 'Bappeda' }}</strong></span>
            <span>Tgl Diarsipkan: <strong>{{ $arsip->tanggal_diarsipkan?->format('d/m/Y') }}</strong></span>
            <span>Status: <strong>{{ strtoupper($arsip->status_retensi) }}</strong></span>
        </div>
    </div>

    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "ARSIP-BAPPEDA-{{ $arsip->id }}-{{ $arsip->no_berkas }}",
            width: 85,
            height: 85,
            colorDark : "#1b3a5c",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.M
        });
    </script>
</body>
</html>
