<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');

    Route::post('/forgot-password', [AuthController::class, 'requestResetPassword'])
        ->name('password.request.store');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'updateProfile'])
        ->name('profile.update');

    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])
        ->name('profile.password');

});