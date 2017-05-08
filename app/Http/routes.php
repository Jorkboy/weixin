<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['prefix' => 'wechat'], function () {
    Route::any('message', 'WXController@message');
    Route::any('index', 'WXController@index');
    Route::any('callback', 'WXController@callback');
    Route::any('oauth', 'WXController@oauth');
    Route::any('menu/{type?}', 'WXController@menu');
    Route::any('getQRCode', 'WXController@getQRCode');
    Route::any('material', 'WXController@materialHandle');
    Route::any('broadcast', 'WXController@broadcast');
});

