<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Plan extends Model
{

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function planType()
	{
		return $this->belongsTo('App\PlanType');
	}

	public function planData()
	{
		return $this->hasMany('App\PlanData');
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
		$sortedPlanData = $this->planData->sortBy('created_at');

		$days = round((strtotime($sortedPlanData->last()->created_at) - strtotime($sortedPlanData->first()->created_at)) / 86400, 0);

		return $days;
	}

	public function getPredictedCompletionDate()
	{
		if ($this->planData->count() > 1)
		{
			$day = 0;
			$m = $this->getSlope();
			$b = $this->getYIntercept();
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

	public function getSlope()
	{
		$sums = $this->calculateSums();
		$n = $this->planData->count();

		$m = $this->calculateM($n, $sums);

		return $m;
	}

	public function getYIntercept()
	{
		$sums = $this->calculateSums();
		$n = $this->planData->count();

		$b = $this->calculateB($n, $sums);

		return $b;
	}

	public function getLinearRegressionLine()
	{
		$sums = $this->calculateSums();
		$n = $this->planData->count();

		$m = $this->calculateM($n, $sums);
		$b = $this->calculateB($n, $sums);

		$result = [];
		$daysOnPlan = $this->getDaysOnPlan();
		for ($i = 0; $i <= $daysOnPlan; $i++)
		{
			$result[] = ($m * $i) + $b;
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
		$sortedPlanData = $this->planData->sortBy('created_at');

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
