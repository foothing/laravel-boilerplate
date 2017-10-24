<?php

Route::get('/', 'App\Http\Controllers\HomeController@getIndex');

Route::get('message', 'App\Http\Controllers\MessageController@getIndex')->name('message');
Route::get('auth/user', 'App\Http\Controllers\Auth\Laravel\LoginController@getUser')->name('auth.user');

Route::group(['middleware' => 'guest'], function () {
    Route::get('auth/login', 'App\Http\Controllers\Auth\Laravel\LoginController@getLogin')->name('auth.login');
    Route::post('auth/login', 'App\Http\Controllers\Auth\Laravel\LoginController@postLogin');

    Route::get('auth/register', 'App\Http\Controllers\Auth\Laravel\RegisterController@getRegister')->name('auth.register');
    Route::get('auth/activate/{token}', 'App\Http\Controllers\Auth\Laravel\RegisterController@getActivate')->name('auth.activate');
    Route::post('auth/register', 'App\Http\Controllers\Auth\Laravel\RegisterController@postRegister');

    Route::get('auth/password', 'App\Http\Controllers\Auth\Laravel\PasswordController@getPassword')->name('auth.password')->name('auth.password');
    Route::post('auth/password', 'App\Http\Controllers\Auth\Laravel\PasswordController@postPassword');
    Route::get('auth/reset/{token}', 'App\Http\Controllers\Auth\Laravel\PasswordController@getReset')->name('auth.reset');
    Route::post('auth/reset', 'App\Http\Controllers\Auth\Laravel\PasswordController@postReset');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('auth/logout', 'App\Http\Controllers\Auth\Laravel\LoginController@getLogout')->name('auth.logout');
});

\Foothing\RepositoryController\Controllers\RouteInstaller::install();
