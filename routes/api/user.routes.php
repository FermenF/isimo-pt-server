<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => '/users'], function () {

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [UserController::class, 'index']);
    });
});
