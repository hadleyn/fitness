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

	public function getLinearRegressionLine()
	{
		$sums = $this->calculateSums();
		$n = $this->planData->count();
		Log::debug('Data count '.$n);

		$m = (($n * $sums['xySum']) - ($sums['xSum'] * $sums['ySum'])) / (($n * $sums['x2Sum']) - ($sums['xSum'] * $sums['xSum']));
		Log::debug('Regression line m='.$m);
		$b = (($sums['x2Sum'] * $sums['ySum']) - ($sums['xSum'] * $sums['xySum'])) / (($n * $sums['x2Sum']) - ($sums['xSum'] * $sums['xSum']));
		Log::debug('Regression line b='.$b);

		//Now we have to create a phony "data set" that is actually just this line
		$result = [];
		for ($i = 0; $i < $n; $i++)
		{
			$result[] = ($m * $i) + $b;
		}
		Log::debug('Line data '.print_r($result, TRUE));
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

		foreach ($this->planData as $pd)
		{
			$x = round((strtotime($pd->created_at) - strtotime($this->planData->get(0)->created_at)) / 86400, 0);
			Log::debug('current - previous '.$x);
			$sums['xSum'] += $x;
			$sums['ySum'] += (float)$pd->data;
			$sums['xySum'] += ((float)$pd->data * $x);
			$sums['x2Sum'] += pow($x, 2);
			$sums['y2Sum'] += pow((float)$pd->data, 2);
		}
		return $sums;
	}
}
