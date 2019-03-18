<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

  public static function getDataOnSimpleDate($simpleDate)
  {
    $result = PlanData::where('simple_date', $simpleDate)->get()->first();

    return $result;
  }
}
