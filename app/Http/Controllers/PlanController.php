<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Plan;
use App\PlanData;
use App\PlanType;
use App\WeightPlanData;
use App\User;
use App\Rules\UserOwnsPlan;

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
}
