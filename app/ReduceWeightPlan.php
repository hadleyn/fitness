<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Rules\UserOwnsPlan;

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

    public function getDataPointType()
    {
      return 'DOUBLE';
    }

    public function getDataPointUnit()
    {
      return 'POUNDS';
    }

    public function getTotalWeightLost()
    {
      if ($this->plan->planData->count() > 0)
      {
    	   return $this->plan->planData->last()->data - $this->starting_weight;
      }
      return 0;
    }

    public function getExpectedLossPerDay()
    {
      //Definded as (goal weight - start weight) / (end date - start date)
      $weightLoss = $this->goal_weight - $this->starting_weight;
      $dateDiff = strtotime($this->goal_date) - strtotime($this->plan->start_date);

      return $weightLoss / round($dateDiff / (60 * 60 * 24), 0);
    }

    public function getExpectedLossData()
    {
      //This is similar to regression, except here we use the start weight as Y intercept
      //and the expected loss per day as the slope.
      $daysOnPlan = $this->plan->getDaysOnPlan();
      $b = $this->starting_weight;
      $m = $this->getExpectedLossPerDay();
  		for ($i = 0; $i <= $daysOnPlan; $i++)
  		{
  			$result[] = ($m * $i) + $b;
  		}

  		return $result;
    }

    public function getDailyDeltas()
    {
      $continuousData = $this->plan->getContinuousDataSet();
      $result = [];
      foreach ($continuousData as $date => $planData)
      {
        $previousDay = date('Y-m-d', strtotime($date. ' -1 day'));
        if ($continuousData->has($previousDay))
        {
          $previousDayPlanData = $continuousData->get($previousDay);
          if ($previousDayPlanData && $planData)
          {
            $result[$date] = number_format($planData->data - $previousDayPlanData->data, 2);
          }
          else
          {
            $result[$date] = 'N/A';
          }
        }
        else
        {
          //This should be the first day
          $result[$date] = 'N/A';
        }
      }

      return $result;
    }

    public function getExpectedDataForDate($date)
    {
      $firstDate = $this->plan->planData->sortBy('simple_date')->first()->simple_date;
      $diff = strtotime($date) - strtotime($firstDate);
      $days = round(($diff / (24 * 60 * 60)), 0);
      $b = $this->starting_weight;
      $m = $this->getExpectedLossPerDay();
  		$result = ($m * $days) + $b;

      return number_format($result, 2);
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

    public function validateReduceWeightData(\Illuminate\Http\Request $request)
    {
      //Validate the incoming data
      $request->validate([
        'data' => 'required|numeric',
        'planId' => new UserOwnsPlan
      ]);
    }
}
