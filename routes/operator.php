<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Operator\DashboardController;
use App\Http\Controllers\Operator\ArsipController;
use App\Http\Controllers\Operator\PeminjamanController;
use App\Http\Controllers\Admin\ReportController;

Route::middleware(['auth', 'role:operator'])
    ->prefix('operator')
    ->name('operator.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/arsip/{arsip}/print-label',
            [ArsipController::class, 'printLabel'])
            ->name('arsip.print-label');

        Route::get('/arsip/{arsip}/download',
            [ArsipController::class, 'download'])
            ->name('arsip.download');

        Route::post('/arsip/preview',
            [\App\Http\Controllers\Admin\ArsipImportController::class, 'preview'])
            ->name('arsip.preview');

        Route::post('/arsip/import',
            [\App\Http\Controllers\Admin\ArsipImportController::class, 'import'])
            ->name('arsip.import');

        Route::resource('arsip', ArsipController::class);

        Route::get('/laporan', [ReportController::class, 'index'])
            ->name('laporan.index');

        Route::get('/laporan/arsip', [ReportController::class, 'arsip'])
            ->name('laporan.arsip');

        Route::get('/laporan/arsip/pdf', [ReportController::class, 'arsipPdf'])
            ->name('laporan.arsip.pdf');

        Route::get('/laporan/arsip/excel', [ReportController::class, 'arsipExcel'])
            ->name('laporan.arsip.excel');

        Route::get('/laporan/peminjaman', [ReportController::class, 'peminjaman'])
            ->name('laporan.peminjaman');

        Route::get('/laporan/peminjaman/pdf', [ReportController::class, 'peminjamanPdf'])
            ->name('laporan.peminjaman.pdf');

        Route::get('/laporan/peminjaman/excel', [ReportController::class, 'peminjamanExcel'])
            ->name('laporan.peminjaman.excel');

        Route::resource('peminjaman', PeminjamanController::class);

        Route::put('/peminjaman/{peminjaman}/approve',
            [PeminjamanController::class, 'approve'])
            ->name('peminjaman.approve');

        Route::put('/peminjaman/{peminjaman}/reject',
            [PeminjamanController::class, 'reject'])
            ->name('peminjaman.reject');

        Route::put('/peminjaman/{peminjaman}/kembalikan',
            [PeminjamanController::class, 'kembalikan'])
            ->name('peminjaman.kembalikan');
    });