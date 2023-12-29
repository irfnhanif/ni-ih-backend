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

Route::post('/register', 'App\Http\Controllers\AuthController@register');
Route::post('/login', 'App\Http\Controllers\AuthController@login');

Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('/', 'App\Http\Controllers\AuthController@user');
    Route::delete('/logout', 'App\Http\Controllers\AuthController@logout');
});

Route::middleware('auth:sanctum')->prefix('books')->group(function () {
    Route::get('/', 'App\Http\Controllers\BookController@index');
    Route::post('/add', 'App\Http\Controllers\BookController@store');
    Route::get('/{book_id}', 'App\Http\Controllers\BookController@show');
    Route::put('/{book_id}/edit', 'App\Http\Controllers\BookController@update');
    Route::delete('/{book_id}', 'App\Http\Controllers\BookController@destroy');
});