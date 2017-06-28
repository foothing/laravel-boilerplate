<?php

Route::get('/', function () {
    return view('app.home');
});

Route::get('message', 'App\Http\Controllers\MessageController@getIndex')->name('message');
Route::get('auth/user', 'App\Http\Controllers\Auth\Sentinel\LoginController@getUser')->name('auth.user');

Route::group(['middleware' => 'guest'], function () {
    Route::get('auth/login', 'App\Http\Controllers\Auth\Sentinel\LoginController@getLogin')->name('auth.login');
    Route::post('auth/login', 'App\Http\Controllers\Auth\Sentinel\LoginController@postLogin');

    Route::get('auth/register', 'App\Http\Controllers\Auth\Sentinel\RegisterController@getRegister')->name('auth.register');
    Route::get('auth/activate/{token}', 'App\Http\Controllers\Auth\Sentinel\RegisterController@getActivate')->name('auth.activate');
    Route::post('auth/register', 'App\Http\Controllers\Auth\Sentinel\RegisterController@postRegister');

    Route::get('auth/password', 'App\Http\Controllers\Auth\Sentinel\PasswordController@getPassword')->name('auth.password');->name('auth.password');
    Route::post('auth/password', 'App\Http\Controllers\Auth\Sentinel\PasswordController@postPassword');
    Route::get('auth/reset/{token}', 'App\Http\Controllers\Auth\Sentinel\PasswordController@getReset')->name('auth.reset');
    Route::post('auth/reset', 'App\Http\Controllers\Auth\Sentinel\PasswordController@postReset');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('auth/logout', 'App\Http\Controllers\Auth\Sentinel\LoginController@getLogout')->name('auth.logout');
});

\Foothing\RepositoryController\Controllers\RouteInstaller::install();
