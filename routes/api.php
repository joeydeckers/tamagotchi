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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/tamagotchi')->group(function(){
    Route::get('/all', 'App\Http\Controllers\TamagotchiController@index');
    Route::post('/create', 'App\Http\Controllers\TamagotchiController@create');
    Route::delete('/delete/{id}', 'App\Http\Controllers\TamagotchiController@destroy');
});

Route::prefix('/hotelroom')->group(function(){
    Route::get('/rooms/{id}', 'App\Http\Controllers\HotelRoomController@index');
    Route::post('/create', 'App\Http\Controllers\HotelRoomController@create');
    Route::put('/update/{id}', 'App\Http\Controllers\HotelRoomController@edit');
    Route::delete('/delete/{id}', 'App\Http\Controllers\HotelRoomController@destroy');
});

Route::prefix('/booking')->group(function(){
    Route::post('/create', 'App\Http\Controllers\BookingController@create');
});

Route::post('/register', 'App\Http\Controllers\AuthController@register');
Route::post('/login', 'App\Http\Controllers\AuthController@login');
