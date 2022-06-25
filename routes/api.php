<?php

use App\Http\Controllers\Api\{TweetController, UserController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::prefix('me')->controller(UserController::class)->group(function () {
        Route::get('/', 'details');
        Route::get('/followings', 'followings');
        Route::get('/follows', 'follows');
    });

    Route::prefix('tweets')->controller(TweetController::class)->group(function () {
        Route::get('/', 'tweets');
        Route::post('/', 'store');

        Route::prefix('{tweet}')->group(function () {
            Route::get('/', 'show');
            Route::get('/replies', 'replies');
            Route::post('/like', 'like');
            Route::delete('/unlike', 'unlike');
            Route::post('/reply', 'reply');
        });
    });
});

