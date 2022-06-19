<?php

use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here are the routes needed for the authentication
|
*/

Route::middleware('guest')->group(function () {
    Route::controller(RegisterController::class)->group(function () {
        Route::get('register','view')->name('register');
        Route::post('register','store');
    });

    Route::controller(AuthenticatedUserController::class)->group(function () {
        Route::get('login','view')->name('login');
        Route::post('login', 'store');
    });
});

Route::middleware('auth')->group(function () {
    Route::controller(VerifyEmailController::class)->group(function () {
        Route::get('verify-email', 'view')->name('verification.notice');
        Route::get('verify-email/{id}/{hash}', 'verify')->middleware(['signed'])->name('verification.verify');
        Route::post('email/verification-notification','store')->name('verification.send');
    });

    Route::post('logout', [AuthenticatedUserController::class, 'logout'])->name('logout');
});
