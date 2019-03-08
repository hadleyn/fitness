<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Exceptions\InvalidPlanException;
use App\PlanData;

class Plan extends Model
{

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	// public function planType()
	// {
	// 	return $this->belongsTo('App\PlanType');
	// }

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
		$sortedPlanData = $this->planData->sortBy('simple_date');

		$days = round((strtotime($sortedPlanData->last()->created_at) - strtotime($sortedPlanData->first()->created_at)) / 86400, 0);

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
			$firstDate = $sortedPlanData->first()->simple_date;
			$lastDate = $sortedPlanData->last()->simple_date;
			$dayCounter = $firstDate;
			$dataSet = [];
			$lastValidData = null;
			while (strtotime($dayCounter) <= strtotime($lastDate))
			{
				$planData = PlanData::where('simple_date', $dayCounter)->get();
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
