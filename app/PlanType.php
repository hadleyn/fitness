<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanType extends Model
{
  const REDUCE_WEIGHT = 2;
	const GAIN_WEIGHT = 1;
	const REDUCE_FAT_PERCENTAGE = 4;
	const GAIN_MUSCLE = 3;
	const WORKOUT = 5; 

  public function plan()
  {
    $this->belongsTo('App\Plan');
  }
}
