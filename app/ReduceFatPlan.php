<?php

namespace App;

use Validator;
use Illuminate\Database\Eloquent\Model;

use App\IPlan;
use App\Helpers\Regression;
use App\Rules\UserOwnsPlan;
use App\Rules\UserOwnsPlanData;

class ReduceFatPlan extends Model implements IPlan
{
  public function plan()
  {
    return $this->morphOne('App\Plan', 'plannable');
  }

  public function getPlanForm()
  {
    return 'dashboard.fatreductionplanform';
  }

  public function getPlanView()
  {
    return 'plan.reducefatdataplan';
  }

  public function getPlanTypeDescription()
  {
    return 'Reduce Fat Percentage';
  }

  public function getDataPointType()
  {
    return 'DOUBLE';
  }

  public function getDataPointUnit()
  {
    return 'PERCENT';
  }

  public function getStartingValue()
  {
    return $this->starting_fat_percentage;
  }

  public function getGoalValue()
  {
    return $this->goal_fat_percentage;
  }

  public function getGoalDate()
  {
    return $this->goal_date;
  }

  public function getPredictedCompletionDate()
  {
    $completeData = $this->plan->getContinuousDataSet();
    if ($this->plan->planData->count() > 1)
    {
      $day = 0;
      $m = Regression::getSlope($completeData);
      $b = Regression::getYIntercept($completeData);
      if ($m >= 0 && $b > $this->goal_fat_percentage)
      {
        //Slope isn't pointing towards goal
        return 'Will never reach goal';
      }
      while (($m * $day) + $b > $this->goal_fat_percentage && $day < 32000)
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

  public function getTotalFatLost()
  {
    if ($this->plan->planData->count() > 0)
    {
       return $this->plan->planData->last()->data - $this->starting_fat_percentage;
    }
    return 0;
  }

  public function validateData(\Illuminate\Http\Request $request)
  {
    //Validate the incoming data
    $request->validate([
      'data' => 'required|numeric|gt:0|lt:100',
      'planId' => new UserOwnsPlan
    ]);
  }

  public function validateDataPointEdit(\Illuminate\Http\Request $request)
  {
    $errors = [];
    $validator = Validator::make($request->all(), [
      'editData' => 'required|numeric|gt:0|lt:100',
      'editDataDate' => 'required|date',
      'planId' => new UserOwnsPlan,
      'planDataId' => new UserOwnsPlanData
    ]);

    $errors = $validator->errors();

    return $errors;
  }
}
