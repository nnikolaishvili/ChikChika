<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TweetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(TweetController::class)->group(function () {
        Route::post('tweets','store')->name('tweet.store');
        Route::get('tweets/{tweet}','show')->name('tweet.show');
        Route::delete('tweets/{tweet}', 'destroy')->name('tweet.destroy');
    });

    Route::get('/{username}', [DashboardController::class, 'dashboard'])->name('dashboard');
});
