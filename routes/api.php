<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(\App\Http\Controllers\API\UserController::class)->group(function () {
    Route::post('login', 'loginUser');
    Route::post('register', 'registerUser');
});

Route::controller(\App\Http\Controllers\API\UserController::class)->group(function () {
    Route::get('user', 'getUserDetail');
    Route::get('logout', 'userLogout');
})->middleware('auth:api');
