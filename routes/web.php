<?php

use App\Http\Controllers\ArsipController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', fn() => redirect()->route('login'));

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::resource('bidang', BidangController::class);
        Route::post('/arsip/import', [ReportController::class, 'importExcel'])->name('arsip.import');
    });

    // Surat Masuk
    Route::resource('surat-masuk', SuratMasukController::class);
    Route::get('/surat-masuk/{surat_masuk}/download', [SuratMasukController::class, 'download'])->name('surat-masuk.download');

    // Surat Keluar
    Route::resource('surat-keluar', SuratKeluarController::class);
    Route::get('/surat-keluar/{surat_keluar}/download', [SuratKeluarController::class, 'download'])->name('surat-keluar.download');

    // Arsip
    Route::resource('arsip', ArsipController::class);
    Route::get('/arsip/{arsip}/download', [ArsipController::class, 'download'])->name('arsip.download');

    // Reports
    Route::get('/report/arsip/excel', [ReportController::class, 'exportExcel'])->name('report.arsip.excel');
    Route::get('/report/arsip/pdf', [ReportController::class, 'exportPdf'])->name('report.arsip.pdf');

    // Peminjaman
    Route::resource('peminjaman', PeminjamanController::class)->except(['edit', 'update']);
    Route::put('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
});
