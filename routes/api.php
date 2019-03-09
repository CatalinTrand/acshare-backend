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

Route::get('/register', 'UserController@store');
Route::get('/login', 'UserController@login');

Route::middleware(['CheckAuth'])->group(function () {
    Route::get('/files', 'FilesController@index');
    Route::get('/files/download', 'FilesController@download');
    Route::get('/files/upload', 'FilesController@store');
    Route::get('/files/delete', 'FilesController@delete');
    Route::get('/files/update', 'FilesController@update');
});
