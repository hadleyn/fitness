<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class Regression
{
  public static function dummy()
  {
    echo 'I\'m a stupid baby';
  }

  public static function getSlope($data)
	{
		$sums = Regression::calculateSums($data);
		$n = $data->count();

		$m = 0;
		if ($n > 1)
		{
			$m = Regression::calculateM($n, $sums);
		}

		return $m;
	}

	public static function getYIntercept($data)
	{
		$sums = Regression::calculateSums($data);
		$n = $data->count();

		$b = 0;
		if ($n > 1)
		{
			$b = Regression::calculateB($n, $sums);
		}

		return $b;
	}

  /**
  * geLinearRegressionData calculates and returns an array of regression data that can
  * be used for graphing or analyzing.
  *
  * @param Collection $data
  * @param int $daysOfData
  *
  * @returns array An array structure
  */
  public static function getLinearRegressionData($data, $daysOfData)
  {
    $sums = Regression::calculateSums($data);
		$n = $data->count();

		$result = [];
		if ($n > 1)
		{
			$m = Regression::calculateM($n, $sums);
			$b = Regression::calculateB($n, $sums);

			for ($i = 0; $i <= $daysOfData; $i++)
			{
				$result[] = ($m * $i) + $b;
			}
		}

		return $result;
  }

  private static function calculateSums($data)
  {
    $sums['xSum'] = 0;
		$sums['ySum'] = 0;
		$sums['xySum'] = 0;
		$sums['x2Sum'] = 0;
		$sums['y2Sum'] = 0;

		foreach ($data as $index => $pd)
		{
      if ($pd->data)
      {
  			$sums['xSum'] += $index;
  			$sums['ySum'] += (float)$pd->data;
  			$sums['xySum'] += ((float)$pd->data * $index);
  			$sums['x2Sum'] += pow($index, 2);
  			$sums['y2Sum'] += pow((float)$pd->data, 2);
      }
		}
		return $sums;
  }

  private static function calculateM($n, $sums)
  {
    if ($sums['x2Sum'] === 0 || $sums['xSum'] === 0 || $n === 0)
    {
      return 0; //Technically this is a NaN situation, but this helps keep things from breaking
    }
    $result = (($n * $sums['xySum']) - ($sums['xSum'] * $sums['ySum'])) / (($n * $sums['x2Sum']) - ($sums['xSum'] * $sums['xSum']));

		return $result;
  }

  private static function calculateB($n, $sums)
  {
    $result = (($sums['x2Sum'] * $sums['ySum']) - ($sums['xSum'] * $sums['xySum'])) / (($n * $sums['x2Sum']) - ($sums['xSum'] * $sums['xSum']));

		return $result;
  }
}

?>
