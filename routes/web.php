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
// Route::get('/dashboard/newplan', 'DashboardController@newPlan');
Route::get('/dashboard/newWeightReductionPlan', 'DashboardController@newWeightReductionPlan');
Route::post('/dashboard/saveReduceWeightPlan', 'DashboardController@saveReduceWeightPlan');
Route::get('/dashboard/newFatReductionPlan', 'DashboardController@newFatReductionPlan');
Route::post('/dashboard/saveReduceFatPlan', 'DashboardController@saveReduceFatPlan');
Route::get('/dashboard/newMuscleGainPlan', 'DashboardController@newMuscleGainPlan');
Route::post('/dashboard/saveGainMusclePlan', 'DashboardController@saveGainMusclePlan');
Route::get('/dashboard/editplan/{planId}', 'DashboardController@editPlan');

//Plan
Route::get('/plan/{planId}', 'PlanController@index')->name('plan');
Route::post('/plan/addData', 'PlanController@addData');
Route::get('/plan/{planId}/datapull', 'PlanController@dataPull');
Route::get('/plan/{planId}/pullDailyDeltaData', 'PlanController@pullDailyDeltaData');
Route::get('/plan/{planId}/pullDailySlopeData', 'PlanController@pullDailySlopeData');
Route::get('/plan/{planId}/rollingAverageDataPull', 'PlanController@rollingAverageDataPull');
Route::get('/plan/{planId}/editDataPoint/{dataPointId}', 'PlanController@editDataPoint');
Route::post('/plan/saveDataPointEdit/', 'PlanController@saveDataPointEdit');
Route::post('/plan/submitBulkDataUpload', 'PlanController@submitBulkDataUpload');
Route::post('/plan/confirmBulkDataImport', 'PlanController@confirmBulkDataImport');
