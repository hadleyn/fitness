<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanType extends Model
{
  public function plan()
  {
    $this->belongsTo('App\Plan');
  }
}
