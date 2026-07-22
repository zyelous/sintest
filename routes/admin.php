<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BidangController;
use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\ArsipImportController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PeminjamanController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('users', UserController::class);

        Route::put('/users/{user}/reset-password',
            [UserController::class, 'resetPassword'])
            ->name('users.reset-password');

        Route::resource('bidang', BidangController::class);

        Route::resource('arsip', ArsipController::class);

        Route::get('/arsip/{arsip}/download',
            [ArsipController::class, 'download'])
            ->name('arsip.download');

        Route::post('/arsip/preview',
            [ArsipImportController::class, 'preview'])
            ->name('arsip.preview');

        Route::post('/arsip/import',
            [ArsipImportController::class, 'import'])
            ->name('arsip.import');

        Route::get('/laporan',
            [ReportController::class, 'index'])
            ->name('laporan.index');

        Route::get('/report/arsip/excel',
            [ReportController::class, 'exportExcel'])
            ->name('report.arsip.excel');

        Route::get('/report/arsip/pdf',
            [ReportController::class, 'exportPdf'])
            ->name('report.arsip.pdf');

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

        Route::get('/laporan', [ReportController::class, 'index'])
    ->name('laporan.index');

Route::get('/laporan/arsip', [ReportController::class, 'arsip'])
    ->name('laporan.arsip');

Route::get('/laporan/peminjaman', [ReportController::class, 'peminjaman'])
    ->name('laporan.peminjaman');

    Route::get('/laporan/arsip/pdf', [ReportController::class, 'arsipPdf'])
    ->name('laporan.arsip.pdf');

Route::get('/laporan/arsip/excel', [ReportController::class, 'arsipExcel'])
    ->name('laporan.arsip.excel');

Route::get('/laporan/peminjaman/pdf', [ReportController::class, 'peminjamanPdf'])
    ->name('laporan.peminjaman.pdf');

Route::get('/laporan/peminjaman/excel', [ReportController::class, 'peminjamanExcel'])
    ->name('laporan.peminjaman.excel');

        });