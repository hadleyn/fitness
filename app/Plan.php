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
		$sortedPlanData = $this->planData->sortBy('simple_date');
		$firstDate = $sortedPlanData->first()->simple_date;
		$lastDate = $sortedPlanData->last()->simple_date;
		$dayCounter = $firstDate;
		$dataSet = [];
		while (strtotime($dayCounter) <= strtotime($lastDate))
		{
			$planData = PlanData::where('simple_date', $dayCounter)->get();
			if ($planData->count() === 1)
			{
				$dataSet[$dayCounter] = $planData->first();
			}
			else
			{
				$dataSet[$dayCounter] = null;
			}
			$dayCounter = date('Y-m-d', strtotime($dayCounter . ' +1 day'));
		}

		return $dataSet;
	}

	public function getDataOnNthDayOfPlan($n)
	{
		$firstPlanDate = $this->planData->sortBy('simple_date')->first()->simple_date;
		return PlanData::where('simple_date', $firstPlanDate);
	}

	public function getPredictedCompletionDate()
	{
		throw new InvalidPlanException('Plan is generic type. Use ->plannable to get specific plan type functions');
	}

	public function getSlope()
	{
		$sums = $this->calculateSums();
		$n = $this->planData->count();

		$m = 0;
		if ($n > 1)
		{
			$m = $this->calculateM($n, $sums);
		}

		return $m;
	}

	public function getYIntercept()
	{
		$sums = $this->calculateSums();
		$n = $this->planData->count();

		$b = 0;
		if ($n > 1)
		{
			$b = $this->calculateB($n, $sums);
		}

		return $b;
	}

	public function getLinearRegressionLine()
	{
		$sums = $this->calculateSums();
		$n = $this->planData->count();

		$result = [];
		if ($n > 1)
		{
			$m = $this->calculateM($n, $sums);
			$b = $this->calculateB($n, $sums);

			$daysOnPlan = $this->getDaysOnPlan();
			for ($i = 0; $i <= $daysOnPlan; $i++)
			{
				$result[] = ($m * $i) + $b;
			}
		}

		return $result;
	}

	public function getRollingAverageDataSet($period = 7)
	{
		$continuousData = collect($this->getContinuousDataSet())->chunk($period);

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

	private function calculateM($n, $sums)
	{
		$result = (($n * $sums['xySum']) - ($sums['xSum'] * $sums['ySum'])) / (($n * $sums['x2Sum']) - ($sums['xSum'] * $sums['xSum']));

		return $result;
	}

	private function calculateB($n, $sums)
	{
		$result = (($sums['x2Sum'] * $sums['ySum']) - ($sums['xSum'] * $sums['xySum'])) / (($n * $sums['x2Sum']) - ($sums['xSum'] * $sums['xSum']));

		return $result;
	}

	/**
	*	X in all cases is time, so we'll need to normalize /**
	* it as a unix timestamp. We'll then be able to translate
	* it back into a real date at any time.
	*/
	private function calculateSums()
	{
		$sums['xSum'] = 0;
		$sums['ySum'] = 0;
		$sums['xySum'] = 0;
		$sums['x2Sum'] = 0;
		$sums['y2Sum'] = 0;
		$sortedPlanData = $this->planData->sortBy('simple_date');

		foreach ($sortedPlanData as $pd)
		{
			$x = round((strtotime($pd->created_at) - strtotime($this->planData->get(0)->created_at)) / 86400, 0);
			$sums['xSum'] += $x;
			$sums['ySum'] += (float)$pd->data;
			$sums['xySum'] += ((float)$pd->data * $x);
			$sums['x2Sum'] += pow($x, 2);
			$sums['y2Sum'] += pow((float)$pd->data, 2);
		}
		return $sums;
	}
}
