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

Route::post('register', 'API\UserController@register');
Route::post('login', 'API\UserController@login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('details', 'API\UserController@details');

    Route::get('projects', 'ProjectController@getAll');
    Route::get('projects/{id}', 'ProjectController@get');
    Route::post('projects', 'ProjectController@create');
    Route::put('projects/{id}', 'ProjectController@update');
    Route::delete('projects/{id}', 'ProjectController@delete');

    Route::get('images', 'ImageController@getAll');
    Route::get('images/project/{projectId}', 'ImageController@getAllByProject');
    Route::post('images', 'ImageController@create');
    Route::delete('images/{id}', 'ImageController@delete');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
