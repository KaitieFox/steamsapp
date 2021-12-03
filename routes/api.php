<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('classes')->group(function () {
    //get
    Route::streams('all', 'App\Http\Controllers\DanceClassesController@getAllClasses');
    Route::streams('/instructor/{instructor}', 'App\Http\Controllers\DanceClassesController@getByInstructor');

    //post
    // it's not working because of a crsf issue.
    Route::streams('create', [
        'uses' => 'App\Http\Controllers\DanceClassesController@create',
        'verb' => 'post'
    ]);

    Route::streams('update', [
        'uses' => 'App\Http\Controllers\DanceClassesController@update',
        'verb' => 'patch'
    ]);
});
