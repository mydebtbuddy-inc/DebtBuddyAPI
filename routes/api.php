<?php

use Illuminate\Http\Request;

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

Route::middleware('guest')->group(function () {
    Route::prefix('auth')->group(function() {
        Route::post('/register', 'Auth\RegistrationController');
        Route::post('/login', 'Auth\LoginController@login');
    });
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function() {
        Route::post('/logout', 'Auth\LoginController@logout');
    });

    Route::prefix('user')->group(function() {
        Route::get('/', function (Request $request) {
            return $request->user();
        });
    });
});
