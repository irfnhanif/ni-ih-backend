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

Route::post('/register', 'App\Http\Controllers\AuthController@register');
Route::post('/login', 'App\Http\Controllers\AuthController@login');

Route::prefix('user')->group(function () {
    Route::get('/', 'App\Http\Controllers\AuthController@user');
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
});

Route::prefix('books')->group(function () {
    Route::get('/', 'App\Http\Controllers\BookController@index');
    Route::post('/', 'App\Http\Controllers\BookController@store');
    Route::get('/{id}', 'App\Http\Controllers\BookController@show');
    Route::put('/{id}', 'App\Http\Controllers\BookController@update');
    Route::delete('/{id}', 'App\Http\Controllers\BookController@destroy');
});