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

Route::group(['namespace' => 'App\Http\Controllers\Web'], function() {
    Route::get('/', 'UserController@login');
});

Route::group(['namespace' => 'App\Http\Controllers\Web','middleware'=>['Permission']], function() {
    Route::get('dashboard', 'DashboardController@index');



});
