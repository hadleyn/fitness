<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ReduceWeightPlan extends Model
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

    public function getExpectedLossPerDay()
    {
      //Definded as (goal weight - start weight) / (end date - start date)
      $weightLoss = $this->goal_weight - $this->starting_weight;
      $dateDiff = strtotime($this->goal_date) - strtotime($this->plan->start_date);

      return $weightLoss / round($dateDiff / (60 * 60 * 24), 0);
    }

    public function getPredictedCompletionDate()
    {
      if ($this->plan->planData->count() > 1)
  		{
  			$day = 0;
  			$m = $this->plan->getSlope();
  			$b = $this->plan->getYIntercept();
  			if ($m >= 0 && $b > $this->goal_weight)
  			{
  				//Slope isn't pointing towards goal
  				return 'Will never reach goal';
  			}
  			while (($m * $day) + $b > $this->goal_weight && $day < 32000)
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
}
