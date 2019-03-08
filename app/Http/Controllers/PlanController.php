<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Regression;

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

    $viewData['displayDateFormat'] = PlanController::DISPLAY_DATE_FORMAT;
    $viewData['plan'] = $plan;
    $viewData['continuousPlanData'] = $plan->getContinuousDataSet();
    $viewData['dailyDeltas'] = $plan->plannable->getDailyDeltas();

    return view($plan->plannable->getPlanView(), $viewData);

  }

  public function submitBulkDataUpload(Request $request)
  {
    $result['errors'] = [];
    try
    {
      Log::debug('File incoming? '.print_r($request->bulkFile, TRUE));
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
      foreach ($parsedData as $rawData)
      {
        $planData = new PlanData();
        $planData->plan_id = $request->planId;
        $planData->data = $rawData[1];
        $planData->simple_date = date('Y-m-d', strtotime($rawData[0]));
        $planData->units = "POUNDS";
        $planData->data_type = "DOUBLE";
        $planData->save();
      }
      Storage::delete($path);
    }
    catch (Exception $e)
    {
      $result['errors'][] = 'There was an error processing your file. Make sure it is correctly formatted';
    }
    echo json_encode($result);
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

  public function dataPull($planId)
  {
    Log::debug('data pull, plan id '.$planId);
    $plan = Plan::find($planId);
    $continuousDataSet = $plan->getContinuousDataSet();

    $result = [];

    foreach ($continuousDataSet as $date => $planData)
    {
      $result['x'][] = date(PlanController::DISPLAY_DATE_FORMAT, strtotime($date));
      if ($planData != null)
      {
        $result['y'][] = $planData->data;
      }
      else
      {
        $result['y'][] = null;
      }
    }

    $result['label'] = 'My Data';
    $result['regression'] = $plan->getLinearRegressionLine();
    $result['expected'] = $plan->plannable->getExpectedLossData();

    echo json_encode($result);
  }

  public function editDataPoint($planId, $dataPointId)
  {
    $planData = PlanData::find($dataPointId);
    $result['data'] = $planData->data;
    $result['date'] = date('m/d/Y', strtotime($planData->created_at));
    $result['planDataId'] = $planData->id;

    echo json_encode($result);
  }

  public function saveDataPointEdit(Request $request)
  {
    $plan = Plan::find($request->planId);
    //First let's figure out what type of plan this is
    $planType = $plan->planType->id;
    switch ($planType)
    {
      case PlanType::REDUCE_WEIGHT:
        $errors = $this->validateSaveWeightDataEdit($request);
        $dataPoint = WeightPlanData::find($request->planDataId);
        break;
    }

    $result['errors'] = [];
    if (count($errors->all()) === 0)
    {
      $dataPoint->data = $request->editData;
      $dataPoint->simple_date = date('Y-m-d', strtotime($request->editDataDate));

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

    $plan->plannable->validateReduceWeightData($request);

    $dataPoint = new PlanData;
    $dataPoint->plan_id = $request->planId;
    $dataPoint->data = $request->data;
    $dataPoint->simple_date = date('Y-m-d');
    $dataPoint->data_type = $plan->plannable->getDataPointType();
    $dataPoint->units = $plan->plannable->getDataPointUnit();

    $dataPoint->save();

    $request->session()->flash('status', 'Data point added successfully!');

    return redirect()->route('plan', ['planId' => $dataPoint->plan_id]);
  }

  private function validateReduceWeightData(Request $request)
  {
    //Validate the incoming data
    $request->validate([
      'data' => 'required|numeric',
      'planId' => new UserOwnsPlan
    ]);
  }

  private function validateSaveWeightDataEdit(Request $request)
  {
    $errors = [];
    $validator = Validator::make($request->all(), [
      'editData' => 'required|numeric',
      'editDataDate' => 'required|date',
      'planId' => new UserOwnsPlan,
      'planDataId' => new UserOwnsPlanData
    ]);

    $errors = $validator->errors();

    return $errors;
  }
}
