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

  public static function getPlanDataOnDate($planId, $date)
  {
    $unixTimestamp = strtotime($date);
    $lowerBound = date('Y-m-d 00:00:00', $unixTimestamp);
    $upperBound = date('Y-m-d 00:00:00', strtotime('+1 day', $unixTimestamp));
    //SELECT * FROM `plan_data` WHERE created_at >='2019-03-05 00:00:00' AND created_at <'2019-03-06 00:00:00'
    $planDataOnDate = DB::table('plan_data')
                        ->select('id')
                        ->where([
                            ['plan_id', '=', $planId],
                            ['created_at', '>=', $lowerBound],
                            ['created_at', '<', $upperBound],
                          ])
                        ->get()->first();
    if ($planDataOnDate !== null)
    {
      $planData = PlanData::find($planDataOnDate->id);
      return $planData->data;
    }
    return null;
  }
}
