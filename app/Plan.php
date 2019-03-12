<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Helpers\Regression;
use App\Exceptions\InvalidPlanException;
use App\PlanData;

class Plan extends Model
{

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function planData()
	{
		return $this->hasMany('App\PlanData');
	}

	public function plannable()
	{
		return $this->morphTo();
	}

	/**
	* This will return the total number of days the plan has been
	* in use. This is NOT necessarily the same as the number of
	* data points in the plan.
	*
	* Days On Plan = Days between first day and last day.
	*/
	public function getDaysOnPlan()
	{
		$days = round((strtotime('now') - strtotime($this->start_date)) / 86400, 0);

		return $days;
	}

	/**
	* Sometimes it's useful to get a continuous data set. This is defined as x
	* values for every day on the plan, and a "N/A" for days with missing data.
	*/
	public function getContinuousDataSet()
	{
		if ($this->planData->count() > 0)
		{
			$sortedPlanData = $this->planData->sortBy('simple_date');
			$firstDate = $this->start_date;
			$lastDate = $sortedPlanData->last()->simple_date;
			$dayCounter = $firstDate;
			$dataSet = [];
			$lastValidData = new PlanData();
			$lastValidData->simple_date = $this->start_date;
			$lastValidData->data = $this->plannable->getStartingValue();
			$lastValidData->estimated = TRUE;
			while (strtotime($dayCounter) <= strtotime($lastDate))
			{
				$planData = PlanData::where('simple_date', $dayCounter)->where('plan_id', $this->id)->get();
				if ($planData->count() === 1)
				{
					$lastValidData = $planData->first();
					$dataSet[] = $lastValidData;
				}
				else
				{
					//If we're missing a data point, fill it with the last valid data point value and flag it
					$tmp = new PlanData();
					$tmp->simple_date = $dayCounter;
					$tmp->data = $lastValidData->data;
					$tmp->estimated = TRUE;
					$dataSet[] = $tmp;
				}
				$dayCounter = date('Y-m-d', strtotime($dayCounter . ' +1 day'));
			}

			return collect($dataSet); //Return the collection, so this isn't an oddball result
		}
		return collect(array());
	}

	public function getDailyDeltas()
	{
		$continuousData = $this->getContinuousDataSet();
		$result = [];
		foreach ($continuousData as $index => $planData)
		{
			if ($continuousData->has($index - 1))
			{
				$previousDayPlanData = $continuousData->get($index - 1);
				if ($previousDayPlanData->data && $planData->data)
				{
					$resultData = new PlanData();
					$resultData->data = number_format($planData->data - $previousDayPlanData->data, 2);
					$resultData->simple_date = $planData->simple_date;
					$result[] = $resultData;
				}
				else
				{
					$resultData = new PlanData();
					$resultData->data = 'N/A';
					$resultData->simple_date = $planData->simple_date;
					$result[] = $resultData;
				}
			}
			else
			{
				//This should be the first day
				$resultData = new PlanData();
				$resultData->data = 'N/A';
				$resultData->simple_date = $planData->simple_date;
				$result[] = $resultData;
			}
		}

		return collect($result);
	}

	public function getDailySlope()
	{
		$continuousData = $this->getContinuousDataSet();

		$i = 1;
		$n = $continuousData->count();
		$result = [];
		while ($i <= $n)
		{
			$result[] = Regression::getSlope($continuousData->slice(0, $i));
			$i++;
		}

		return collect($result);
	}

	public function getExpectedDataForDate($date)
	{
		$firstDate = $this->start_date;
		$diff = strtotime($date) - strtotime($firstDate);
		$days = round(($diff / (24 * 60 * 60)), 0); //Not sure why this -1 has to be here.
		$b = $this->plannable->getStartingValue();
		$m = $this->getExpectedLossPerDay();
		$result = ($m * $days) + $b;

		return number_format($result, 2);
	}

	public function getExpectedLossData()
	{
		//This is similar to regression, except here we use the start weight as Y intercept
		//and the expected loss per day as the slope.
		$daysOnPlan = $this->getDaysOnPlan();
		$b = $this->plannable->getStartingValue();
		$m = $this->getExpectedLossPerDay();
		for ($i = 0; $i <= $daysOnPlan; $i++)
		{
			$result[] = ($m * $i) + $b;
		}

		return $result;
	}

	public function getExpectedLossPerDay()
  {
    //Defined as (goal fat - start fat) / (end date - start date)
    $loss = $this->plannable->getGoalValue() - $this->plannable->getStartingValue();
    $dateDiff = strtotime($this->plannable->getGoalDate()) - strtotime($this->start_date);

    return $loss / round($dateDiff / (60 * 60 * 24), 0);
  }

	public function getRollingAverageDataSet($period = 7)
	{
		$continuousData = $this->getContinuousDataSet()->chunk($period);

		$result = [];
		foreach ($continuousData as $dataChunk)
		{
			$tmpSum = 0;
			foreach ($dataChunk as $d)
			{
				if ($d != null)
				{
					$tmpSum += $d->data;
				}
			}
			$result[] = ['date' => $dataChunk->first()->simple_date,
										'average' => ($tmpSum / $dataChunk->count())];
		}

		return $result;
	}
}
