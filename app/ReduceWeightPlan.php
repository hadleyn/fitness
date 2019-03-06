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

    public function getPredictedCompletionDate()
    {
      if ($this->plan->planData->count() > 1)
  		{
  			$day = 0;
  			$m = $this->plan->getSlope();
  			$b = $this->plan->getYIntercept();
  			if ($m >= 0 && $b > $this->goal)
  			{
  				//Slope isn't pointing towards goal
  				return 'Will never reach goal';
  			}
  			while (($m * $day) + $b > $this->goal)
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
