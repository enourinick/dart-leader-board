<?php

use Illuminate\Http\Request;
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

Route::as('api.') ->group( function () {
    Route::apiResource('user', 'UserController', ['only' => ['index', 'store']]);

    Route::middleware('auth:api')->group(function () {
        Route::get('/me', 'UserController@show')->name('me.show');
        Route::put('/me', 'UserController@update')->name('me.update');
    });
});
