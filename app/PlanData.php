<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanData extends Model
{
  protected $table = 'plan_data';

  

  public function plan()
  {
    return $this->belongsTo('App\Plan');
  }
}
