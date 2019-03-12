<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\IPlan;
use App\Helpers\Regression;
use App\Rules\UserOwnsPlan;

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

  public function getPredictedCompletionDate()
  {
    if ($this->plan->planData->count() > 1)
    {
      $day = 0;
      $m = Regression::getSlope($this->plan->planData);
      $b = Regression::getYIntercept($this->plan->planData);
      if ($m >= 0 && $b > $this->goal_fat_percentage)
      {
        //Slope isn't pointing towards goal
        return 'Will never reach goal';
      }
      while (($m * $day) + $b > $this->goal_fat_percentage && $day < 32000)
      {
        $day++;
      }

      return date('Y-m-d', strtotime('now +'.$day.' days'));
    }
    else
    {
      return "Not Enough Data";
    }
  }

  public function getExpectedLossPerDay()
  {
    //Defined as (goal fat - start fat) / (end date - start date)
    $fatLoss = $this->goal_fat_percentage - $this->starting_fat_percentage;
    $dateDiff = strtotime($this->goal_date) - strtotime($this->plan->start_date);

    return $fatLoss / round($dateDiff / (60 * 60 * 24), 0);
  }

  public function getTotalFatLost()
  {
    if ($this->plan->planData->count() > 0)
    {
       return $this->plan->planData->last()->data - $this->starting_fat_percentage;
    }
    return 0;
  }

  public function getExpectedDataForDate($date)
  {
    $firstDate = $this->plan->start_date;
    $diff = strtotime($date) - strtotime($firstDate);
    $days = round(($diff / (24 * 60 * 60)), 0) - 1;
    $b = $this->starting_fat_percentage;
    $m = $this->getExpectedLossPerDay();
    $result = ($m * $days) + $b;

    return number_format($result, 2);
  }

  public function validateData(\Illuminate\Http\Request $request)
  {
    //Validate the incoming data
    $request->validate([
      'data' => 'required|numeric|gt:0|lt:100',
      'planId' => new UserOwnsPlan
    ]);
  }
}
