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
    return view('graph-chart');
});
Route::get('/graph-chart', function () {
    return view('graph-chart');
});

Route::get('/graph-data', 'ChartController@getGraphData');
Route::get('/graph-bar-data', 'ChartController@getBarGraphData');
Route::get('/graph-line-data', 'ChartController@getLineGraphData');


// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
