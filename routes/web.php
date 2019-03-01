<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', 'MainController@index');

Route::get('/hello', 'HelloController@index');

Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::get('/dashboard/newplan', 'DashboardController@newPlan');
Route::post('/dashboard/saveplan', 'DashboardController@savePlan');
Route::get('/dashboard/editplan/{planId}', 'DashboardController@editPlan');
