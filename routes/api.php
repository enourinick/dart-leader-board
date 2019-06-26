<?php

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

Route::as('api.')->group(function () {
    Route::apiResource('user', 'UserController', ['only' => ['index', 'store']]);

    Route::middleware('auth:api')->group(function () {
        Route::get('/me', 'UserController@show')->name('me.show');
        Route::put('/me', 'UserController@update')->name('me.update');
    });

    Route::apiResource('game', 'GameController', ['only' => ['index', 'show', 'store', 'update']]);
    Route::prefix('game/{game}')->as('game.')->group(function () {
        Route::post('join', 'GameController@join')->name('join');
        Route::delete('left', 'GameController@left')->name('left');
        Route::post('invite', 'GameController@invite')->name('invite');
        Route::post('kick', 'GameController@kick')->name('kick');
        Route::post('score', 'GameController@addScore')->name('score');
    });
});
