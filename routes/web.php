<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SocialiteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/redirect', [SocialiteController::class, 'redirect'])->name('redirect.google');
Route::get('/auth/callback', [SocialiteController::class, 'callback'])->name('callback.google');