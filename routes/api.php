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
Route::post('login','AuthController@login'); //Route post Login
Route::post('register','AuthController@register'); // Route post  register
Route::post('forgot','ForgotController@forgot'); // Route post Forgot password
Route::post('reset','ForgotController@reset'); // Route post Forgot password 
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user','AuthController@getuser');
}); // Routes group with middleware auth api 