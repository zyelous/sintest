<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/operator.php';