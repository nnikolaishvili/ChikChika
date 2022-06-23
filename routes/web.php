<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\UserController;
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
    Route::controller(TweetController::class)->name('tweet.')->group(function () {
        Route::post('/tweets','store')->name('store');

        Route::prefix('tweets/{tweet}')->group(function () {
            Route::get('/','show')->name('show');
            Route::post('/', 'destroy')->name('destroy');
            Route::post('/comment', 'storeComment')->name('comment.store');
            Route::post('/comment/{comment}', 'destroyComment')->name('comment.destroy');
        });
    });

    Route::get('/profile', [UserController::class, 'getProfile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile']);

    Route::get('/{username}', [DashboardController::class, 'dashboard'])->name('dashboard');
});
