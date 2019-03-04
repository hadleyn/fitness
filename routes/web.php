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

//Dashboard
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::get('/dashboard/newplan', 'DashboardController@newPlan');
Route::post('/dashboard/saveplan', 'DashboardController@savePlan');
Route::get('/dashboard/editplan/{planId}', 'DashboardController@editPlan');

//Plan
Route::get('/plan/{planId}', 'PlanController@index')->name('plan');
Route::post('/plan/addData', 'PlanController@addData');
Route::get('/plan/{planId}/datapull', 'PlanController@dataPull');
Route::get('/plan/{planId}/editDataPoint/{dataPointIndex}', 'PlanController@editDataPoint');
Route::post('/plan/saveDataPointEdit/', 'PlanController@saveDataPointEdit');
