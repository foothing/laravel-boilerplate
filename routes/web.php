<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('app.home');
});

Route::get('auth/login', 'App\Http\Controllers\Auth\Sentinel\LoginController@getLogin');
Route::get('auth/logout', 'App\Http\Controllers\Auth\Sentinel\LoginController@getLogout');
Route::get('auth/user', 'App\Http\Controllers\Auth\Sentinel\LoginController@getUser');
Route::post('auth/login', 'App\Http\Controllers\Auth\Sentinel\LoginController@postLogin');

Route::get('auth/register', 'App\Http\Controllers\Auth\Sentinel\RegisterController@getRegister');
Route::get('auth/activate/{token}', 'App\Http\Controllers\Auth\Sentinel\RegisterController@getActivate');
Route::post('auth/register', 'App\Http\Controllers\Auth\Sentinel\RegisterController@postRegister');
