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

        Route::put('/users/reset-requests/{resetRequest}/approve',
            [UserController::class, 'approveResetRequest'])
            ->name('users.reset-requests.approve');

        Route::put('/users/reset-requests/{resetRequest}/reject',
            [UserController::class, 'rejectResetRequest'])
            ->name('users.reset-requests.reject');

        Route::resource('bidang', BidangController::class);

        Route::get('/arsip/{arsip}/print-label',
            [ArsipController::class, 'printLabel'])
            ->name('arsip.print-label');

        Route::get('/arsip/{arsip}/download',
            [ArsipController::class, 'download'])
            ->name('arsip.download');

        Route::post('/arsip/preview',
            [ArsipImportController::class, 'preview'])
            ->name('arsip.preview');

        Route::post('/arsip/import',
            [ArsipImportController::class, 'import'])
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

        Route::get('/report/arsip/excel', [ReportController::class, 'exportExcel'])
            ->name('report.arsip.excel');

        Route::get('/report/arsip/pdf', [ReportController::class, 'exportPdf'])
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

        // Notifikasi — read-all harus didefinisikan SEBELUM {id} agar tidak tertangkap sebagai wildcard
        Route::post('/notifications/read-all', function () {
            auth()->user()->unreadNotifications->markAsRead();
            return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
        })->name('notifications.read-all');

        Route::post('/notifications/{id}/read', function (string $id) {
            auth()->user()->notifications()->where('id', $id)->update(['read_at' => now()]);
            $n = auth()->user()->notifications()->where('id', $id)->first();
            $url = $n?->data['url'] ?? route('admin.peminjaman.index');
            return redirect($url);
        })->name('notifications.read');
    });