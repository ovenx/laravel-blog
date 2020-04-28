<?php

use Illuminate\Http\Request;

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
Route::namespace('Api')->group(function () {
    Route::prefix('admin')->namespace('Admin')->group(function () {
        Route::post('login', 'AdminController@login');
    });
    Route::prefix('admin')->namespace('Admin')->middleware('auth:admin')->group(function () {
        Route::post('logout', 'AdminController@logout');
        Route::put('reset_password', 'AdminController@resetPassword');

        Route::get('posts', 'PostController@lists');
        Route::get('posts/{id}', 'PostController@info');
        Route::post('posts', 'PostController@add');
        Route::put('posts/{id}', 'PostController@edit');
        Route::delete('posts/{id}', 'PostController@delete');

        Route::get('categories', 'CategoryController@lists');
        Route::post('categories', 'CategoryController@add');
        Route::put('categories/{id}', 'CategoryController@edit');
        Route::delete('categories/{id}', 'CategoryController@delete');

        Route::get('tags', 'TagController@lists');
        Route::post('tags', 'TagController@add');
        Route::put('tags/{id}', 'TagController@edit');
        Route::delete('tags/{id}', 'TagController@delete');
    });

    Route::prefix('home')->namespace('Home')->group(function () {
        Route::get('posts', 'PostController@lists');
        Route::get('posts/{id}', 'PostController@info');

        Route::get('categories', 'CategoryController@lists');
        Route::get('categories/{id}', 'PostController@listsByCategory');

        Route::get('tags', 'TagController@lists');
        Route::get('tags/{id}', 'PostController@listsByTag');

        Route::get('archives', 'PostController@listsByArchive');
    });
});
