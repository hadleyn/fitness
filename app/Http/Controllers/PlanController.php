<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Plan;
use App\PlanData;
use App\PlanType;
use App\WeightPlanData;
use App\User;
use App\Rules\UserOwnsPlan;
use App\Rules\UserOwnsPlanData;

class PlanController extends BehindLoginController
{

  public function index($planId)
  {
    Log::debug('Viewing a plan '.$planId);
    //Get all the associated data for this plan

    $plan = Plan::find($planId);
    $planData = $plan->planData;

    $viewData['plan'] = $plan;
    $viewData['planData'] = $planData;

    Log::debug('Plan type id '.$plan->planType->id);
    if ($plan->planType->id == PlanType::REDUCE_WEIGHT)
    {
      return view('plan.weightdataplan', $viewData);
    }
    else if ($plan->planType->id == PlanType::REDUCE_FAT_PERCENTAGE)
    {
      return view('plan.reducefatdataplan', $viewData);
    }
    else if ($plan->planType->id == PlanType::GAIN_MUSCLE)
    {
      return view('plan.gainmuscledataplan', $viewData);
    }
    else if ($plan->planType->id == PlanType::GAIN_WEIGHT)
    {
      return view('plan.gainweightdataplan', $viewData);
    }
    else if ($plan->planType->id == PlanType::WORKOUT)
    {
      return view('plan.workoutdataplan', $viewData);
    }
    else
    {
      Log::error("I can haz error?");
      //error?
    }
  }

  public function submitBulkDataUpload(Request $request)
  {
    Log::debug('File incoming? '.print_r($request->bulkFile, TRUE));
    $path = $request->file('bulkFile')->store('tmp');

    Log::debug('Path for uploaded file '.$path);
  }

  public function dataPull($planId)
  {
    Log::debug('data pull, plan id '.$planId);
    $plan = Plan::find($planId);
    $planData = $plan->planData;
    $sortedPlanData = $planData->sortBy('created_at');

    $result = [];
    $daysOnPlan = $plan->getDaysOnPlan();
    $firstDay = strtotime($sortedPlanData->first()->created_at);
    for ($i = 0; $i <= $daysOnPlan; $i++)
    {
      $currentDay = date('m/d/Y', strtotime('+'.$i.' days', $firstDay));
      $result['x'][] = $currentDay;
      $result['y'][] = PlanData::getPlanDataOnDate($planId, $currentDay);
    }

    $result['label'] = 'Pounds';
    $result['regression'] = $plan->getLinearRegressionLine();
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
      $dataPoint->created_at = date('Y-m-d H:i:s', strtotime($request->editDataDate));

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
    $planType = $plan->planType->id;
    switch ($planType)
    {
      case PlanType::REDUCE_WEIGHT:
        $this->validateReduceWeightData($request);
        $dataPoint = new WeightPlanData();
        break;
    }

    $dataPoint->plan_id = $request->planId;
    $dataPoint->data = $request->data;
    $dataPoint->data_type = $dataPoint->getDataType();
    $dataPoint->units = $dataPoint->getUnits();

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
