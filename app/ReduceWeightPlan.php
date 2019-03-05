<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReduceWeightPlan extends Model
{
    public function plan()
    {
      return $this->morphOne('App\Plan', 'plannable');
    }
}
