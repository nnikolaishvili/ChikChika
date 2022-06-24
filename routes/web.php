<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
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
    Route::get('/home', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::controller(TweetController::class)->name('tweet.')->group(function () {
        Route::post('/tweets','store')->name('store');

        Route::prefix('tweets/{tweet}')->group(function () {
            Route::post('/', 'destroy')->name('destroy');
            Route::post('/like', 'toggleLike')->name('like');
            Route::post('/comment', 'storeComment')->name('comment.store');
            Route::post('/comment/{comment}', 'destroyComment')->name('comment.destroy');
        });
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/{user:username}/settings', 'getSettings')->name('settings');
        Route::post('/{user:username}/settings', 'updateSettings');
        Route::post('/user/{user}/follow', 'toggleFollow')->name('user.follow');
    });

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
});

Route::get('/tweets/{tweet}', [TweetController::class, 'show'])->name('tweet.show');
Route::get('/{user:username}', [UserController::class, 'getProfile'])->name('profile');
