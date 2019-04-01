<?php

namespace App;

use Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Helpers\Regression;
use App\Rules\UserOwnsPlan;
use App\Rules\UserOwnsPlanData;
use App\IPlan;

class ReduceWeightPlan extends Model implements IPlan
{
    public function plan()
    {
      return $this->morphOne('App\Plan', 'plannable');
    }

    public function getPlanForm()
    {
      return 'dashboard.weightreductionplanform';
    }

    public function getPlanView()
    {
      return 'plan.weightdataplan';
    }

    public function getPlanTypeDescription()
    {
      return 'Reduce Weight';
    }

    public function getDataPointType()
    {
      return 'DOUBLE';
    }

    public function getDataPointUnit()
    {
      return 'POUNDS';
    }

    public function getStartingValue()
    {
      return $this->starting_weight;
    }

    public function getGoalValue()
    {
      return $this->goal_weight;
    }

    public function getGoalDate()
    {
      return $this->goal_date;
    }

    public function getTotalWeightLost()
    {
      if ($this->plan->planData->count() > 0)
      {
    	   return $this->plan->planData->last()->data - $this->starting_weight;
      }
      return 0;
    }

    public function getPredictedCompletionDate()
    {
      if ($this->plan->planData->count() > 1)
  		{
  			$completeData = $this->plan->getContinuousDataSet();
  			$day = 0;
  			$m = Regression::getSlope($completeData);
  			$b = Regression::getYIntercept($completeData);
  			if ($m >= 0 && $b > $this->goal_weight)
  			{
  				//Slope isn't pointing towards goal
  				return 'Will never reach goal';
  			}
  			while (($m * $day) + $b >= $this->goal_weight && $day < 32000)
  			{
  				$day++;
  			}
  			return date('Y-m-d', strtotime('+'.$day.' days', strtotime($this->plan->start_date)));
  		}
  		else
  		{
  			return "Not Enough Data";
  		}
    }

    public function validateData(\Illuminate\Http\Request $request)
    {
      //Validate the incoming data
      $request->validate([
        'data' => 'required|numeric',
        'planId' => new UserOwnsPlan
      ]);
    }

    public function validateDataPointEdit(\Illuminate\Http\Request $request)
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
