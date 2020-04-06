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

Route::get(
    '/',
    function () {
        return view('app');
    }
);
Route::get(
    '/cache',
    function () {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');

        return "Cache is cleared";
    }
);
Route::post('/install', ['as' => 'install', 'uses' => 'ShopifyController@install']);
Route::get('/auth', 'ShopifyController@auth');
Route::get('/logout', 'Auth\LoginController@logout');
Route::post('/message/create', ['as' => 'postMessage', 'uses' => 'ProductMessageController@store']);
Route::get('/{slug}', ['as' => 'detail', 'uses' => 'ProductController@detail']);
