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

Route::get('/',             ['as' => 'index',           'uses' => 'ProjectionController@index']);
Route::get('/team',         ['as' => 'team',            'uses' => 'ProjectionController@team']);
Route::get('/free-agent',   ['as' => 'free-agent',      'uses' => 'ProjectionController@freeAgent']);
Route::get('/dfs',          ['as' => 'dfs',             'uses' => 'ProjectionController@dfs']);

