<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Operator\DashboardController;
use App\Http\Controllers\Operator\ArsipController;
use App\Http\Controllers\Operator\PeminjamanController;

Route::middleware(['auth', 'role:operator'])
    ->prefix('operator')
    ->name('operator.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('arsip', ArsipController::class);

        Route::get('/arsip/{arsip}/download',
            [ArsipController::class, 'download'])
            ->name('arsip.download');

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