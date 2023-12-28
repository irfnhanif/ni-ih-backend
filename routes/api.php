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


Route::get('/books', 'App\Http\Controllers\BookController@index');
Route::post('/books', 'App\Http\Controllers\BookController@store');
Route::get('/books/{id}', 'App\Http\Controllers\BookController@show');
Route::put('/books/{id}', 'App\Http\Controllers\BookController@update');
Route::delete('/books/{id}', 'App\Http\Controllers\BookController@destroy');