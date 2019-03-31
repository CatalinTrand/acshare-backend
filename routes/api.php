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

Route::post('/register', 'UserController@store');
Route::post('/login', 'UserController@login');

Route::middleware(['CheckAuth'])->group(function () {
    Route::get('/files', 'FileController@index');
    Route::get('/files/download', 'FileController@download');
    Route::post('/files/upload', 'FileController@store');
    Route::delete('/files/delete', 'FileController@delete');
    Route::put('/files/update', 'FileController@update');
});
