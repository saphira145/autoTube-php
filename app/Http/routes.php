<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('youtube/index', 'YoutubeController@index');
    Route::get('video/index', ['as' => 'video.index', 'uses' => 'VideoController@index']);
    Route::post('video/getVideoList', ['as' => 'video.getVideoList', 'uses' => 'VideoController@getVideoList']);
    Route::post('video/saveVideo', ['as' => 'video.saveVideo', 'uses' => 'VideoController@saveVideo']);
    Route::get('video/encode', ['as' => 'video.encode', 'uses' => 'VideoController@encode']);
    Route::get('video/upload', ['as' => 'video.upload', 'uses' => 'VideoController@upload']);
    Route::get('video/{id}/remove', ['as' => 'video.remove', 'uses' => 'VideoController@remove']);

    //Media
    Route::get('media/index', ['as' => 'media.index', 'uses' => 'MediaController@index']);
    Route::post('media/upload', ['as' => 'media.upload', 'uses' => 'MediaController@upload']);
    Route::post('media/remove', ['as' => 'media.remove', 'uses' => 'MediaController@remove']);
    Route::post('media/extract', ['as' => 'media.extract', 'uses' => 'MediaController@extract']);

    //Log
    Route::post('log/getLogs', ['as' => 'log.getLogs', 'uses' => 'LogController@getLogs']);

    Route::get('auth', ['as' => 'auth.index', 'uses' => 'AuthController@index']);
    Route::get('auth/callback', ['as' => 'auth.callback', 'uses' => 'AuthController@callback']);
    Route::get('auth/logout', ['as' => 'auth.logout', 'uses' => 'AuthController@logout']);
});
