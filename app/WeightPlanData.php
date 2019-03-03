<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PlanData;

class WeightPlanData extends PlanData implements IPlanData
{
    /**
     * Returns the type of data this will be
     */
    public function getDataType()
    {
      return "DOUBLE";
    }

    public function getUnits()
    {
      return "POUNDS";
    }
}
