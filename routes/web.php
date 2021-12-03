<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('classes')->group(function () {
    //get
    Route::streams('all', 'App\Http\Controllers\DanceClassesController@getAllClasses');
    Route::streams('/instructor/{instructor}','App\Http\Controllers\DanceClassesController@getByInstructor');
    
    //post
    // it's not working because of a crsf issue.
    Route::streams('new', [
        'uses' => 'App\Http\Controllers\DanceClassesController@create',
        'verb' => 'post'
    ]);

    
});
