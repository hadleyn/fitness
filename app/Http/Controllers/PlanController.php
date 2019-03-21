<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Regression;
use App\Helpers\DateHelper;

use App\Plan;
use App\PlanData;
use App\PlanType;
use App\WeightPlanData;
use App\User;
use App\Rules\UserOwnsPlan;
use App\Rules\UserOwnsPlanData;

class PlanController extends BehindLoginController
{

  const DISPLAY_DATE_FORMAT = 'm/d/Y';

  public function index($planId)
  {
    //Get all the associated data for this plan
    $plan = Plan::find($planId);

    $continuousData = $plan->getContinuousDataSet();
    $viewData['displayDateFormat'] = PlanController::DISPLAY_DATE_FORMAT;
    $viewData['plan'] = $plan;
    $viewData['continuousPlanData'] = $continuousData;
    $viewData['dailyDeltas'] = $plan->getDailyDeltas();
    $viewData['slope'] = Regression::getSlope($continuousData);
    $viewData['yIntercept'] = Regression::getYIntercept($continuousData);
    $viewData['dataForToday'] = $plan->getPlanDataOnSimpleDate(DateHelper::localTimestamp('Y-m-d'));

    return view($plan->plannable->getPlanView(), $viewData);

  }

  public function confirmBulkDataImport(Request $request)
  {
    $planIds = $request->planIds;
    $data = $request->data;
    $simpleDates = $request->simpleDates;
    $use = $request->checkToUse;
    foreach ($planIds as $index => $iPlanId)
    {
      if (isset($use[$iPlanId]))
      {
        Log::debug("Found some data that we're going to use ".print_r($data));
      }
    }
  }

  public function submitBulkDataUpload(Request $request)
  {
    $result['errors'] = [];
    try
    {
      $path = $request->file('bulkFile')->store('tmp');
      $fileData = Storage::get($path);
      $lines = explode(PHP_EOL, $fileData);
      $parsedData = [];
      foreach ($lines as $line) {
          $parsedData[] = str_getcsv($line);
      }
      Log::debug('parsed data '.print_r($parsedData, TRUE));
      //Discard the first item, it's a header row
      unset($parsedData[0]);
      $unconfirmedData = [];
      foreach ($parsedData as $rawData)
      {
        $planData = new PlanData();
        $planData->plan_id = $request->planId;
        $planData->data = $rawData[1];
        $planData->simple_date = date('Y-m-d', strtotime($rawData[0]));
        $unconfirmedData[] = ['importedData' => $planData,
                              'existingData' => PlanData::getDataOnSimpleDate($planData->simple_date)];
      }
      Storage::delete($path);
    }
    catch (Exception $e)
    {
      $result['errors'][] = 'There was an error processing your file. Make sure it is correctly formatted';
    }

    $viewData['unconfirmedData'] = $unconfirmedData;
    return view('plan.bulkuploadconfirm', $viewData);
  }

  public function rollingAverageDataPull($planId)
  {
    Log::debug('data pull, plan id '.$planId);
    $plan = Plan::find($planId);
    $dataSet = $plan->getRollingAverageDataSet();

    $result = [];
    foreach ($dataSet as $dataObj)
    {
      $result['x'][] = $dataObj['date'];
      $result['y'][] = $dataObj['average'];
    }
    $result['label'] = 'My Data';
    echo json_encode($result);
  }

  public function pullDailySlopeData($planId)
  {
    $plan = Plan::find($planId);
    $continuousData = $plan->getContinuousDataSet();
    $dailySlope = $plan->getDailySlope();

    $result = [];

    foreach ($continuousData as $planData)
    {
      $result['x'][] = date(PlanController::DISPLAY_DATE_FORMAT, strtotime($planData->simple_date));
    }
    foreach ($dailySlope as $ds)
    {
      $result['y'][] = $ds;
    }
    $result['label'] = 'Daily Slope';

    echo json_encode($result);
  }

  public function pullDailyDeltaData($planId)
  {
    $plan = Plan::find($planId);
    $continuousData = $plan->getContinuousDataSet();
    $dailyDeltas = $plan->getDailyDeltas();
    $average = $dailyDeltas->avg('data');
    $target = $plan->getExpectedLossPerDay();

    $result = [];

    foreach ($continuousData as $planData)
    {
      $result['x'][] = date(PlanController::DISPLAY_DATE_FORMAT, strtotime($planData->simple_date));
    }
    $dailyDeltaRegression  = Regression::getLinearRegressionData($dailyDeltas, $dailyDeltas->count());
    foreach ($dailyDeltas as $dd)
    {
      $result['y'][] = $dd->data;
      $result['average'][] = $average;
      $result['target'][] = $target;
    }
    $result['label'] = 'Daily Delta';

    echo json_encode($result);
  }

  public function dataPull($planId)
  {
    Log::debug('data pull, plan id '.$planId);
    $plan = Plan::find($planId);
    $continuousDataSet = $plan->getContinuousDataSet();

    $result = [];

    foreach ($continuousDataSet as $planData)
    {
      $result['x'][] = date(PlanController::DISPLAY_DATE_FORMAT, strtotime($planData->simple_date));
      if ($planData->data != null)
      {
        $result['y'][] = $planData->data;
      }
      else
      {
        $result['y'][] = null;
      }
    }

    $result['label'] = 'My Data';
    $result['regression'] = Regression::getLinearRegressionData($continuousDataSet, $plan->getDaysOnPlan());
    $result['expected'] = $plan->getExpectedLossData();

    echo json_encode($result);
  }

  public function editDataPoint($planId, $dataPointId, $simpleDate)
  {
    $planData = PlanData::find($dataPointId);
    if ($planData)
    {
      $result['data'] = $planData->data;
      $result['date'] = date('m/d/Y', strtotime($planData->simple_date));
      $result['planDataId'] = $planData->id;
    }
    else
    {
      $result['data'] = '';
      $result['date'] = $simpleDate;
      $result['planDataId'] = null;
    }

    echo json_encode($result);
  }

  public function saveDataPointEdit(Request $request)
  {
    $plan = Plan::find($request->planId);
    $errors = $plan->plannable->validateDataPointEdit($request);
    $dataPoint = PlanData::find($request->planDataId);
    if (!$dataPoint)
    {
      $dataPoint = new PlanData;
      $dataPoint->plan_id = $request->planId;
      $dataPoint->simple_date = $request->editDataDate;
    }

    $result['errors'] = [];
    if (count($errors->all()) === 0)
    {
      $dataPoint->data = $request->editData;

      $dataPoint->save();
    }
    else
    {
      foreach ($errors->all() as $error)
      {
        $result['errors'][] = $error;
      }
    }

    echo json_encode($result);
  }

  public function addData(Request $request)
  {
    $plan = Plan::find($request->planId);
    //First let's figure out what type of plan this is

    $plan->plannable->validateData($request);

    $dataPoint = new PlanData;
    $dataPoint->plan_id = $request->planId;
    $dataPoint->data = $request->data;
    $dataPoint->simple_date = DateHelper::localTimestamp('Y-m-d');

    $dataPoint->save();

    $request->session()->flash('status', 'Data point added successfully!');

    return redirect()->route('plan', ['planId' => $dataPoint->plan_id]);
  }

}
