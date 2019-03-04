<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanData extends Model implements IPlanData
{
  protected $table = 'plan_data';

  public function plan()
  {
    return $this->belongsTo('App\Plan');
  }

  public function getDataType()
  {
    echo "Plan data is generic and has no data type";
  }

  public function getUnits()
  {
    echo "Plan data is generic and has no units";
  }
}