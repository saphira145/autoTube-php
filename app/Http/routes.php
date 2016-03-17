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

Route::get('youtube/index', 'YoutubeController@index');
Route::get('video/index', ['as' => 'video.index', 'uses' => 'VideoController@index']);
Route::post('video/getVideoList', ['as' => 'video.getVideoList', 'uses' => 'VideoController@getVideoList']);
Route::post('video/saveVideo', ['as' => 'video.saveVideo', 'uses' => 'VideoController@saveVideo']);

//Media
Route::get('media/index', ['as' => 'media.index', 'uses' => 'MediaController@index']);
Route::post('media/upload', ['as' => 'media.upload', 'uses' => 'MediaController@upload']);
Route::post('media/remove', ['as' => 'media.remove', 'uses' => 'MediaController@remove']);
Route::post('media/extract', ['as' => 'media.extract', 'uses' => 'MediaController@extract']);

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
    //
});
