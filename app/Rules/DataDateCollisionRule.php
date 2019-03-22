<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

use App\Plan;

class DataDateCollisionRule implements Rule
{
    protected $planId;
    protected $startDate;
    protected $goalDate;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($planId, $startDate, $goalDate)
    {
        $this->planId = $planId;
        $this->startDate = $startDate;
        $this->goalDate = $goalDate;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (empty($this->planId))
        {
          return TRUE;
        }
        $plan = Plan::find($this->planId);
        $firstPlanData = $plan->planData->sortBy('simple_date')->first();
        Log::debug('first plan data date '.$this->startDate);
        $lastPlanData = $plan->planData->sortBy('simple_date')->last();
        Log::debug('last plan data date '.$this->goalDate);
        if (strtotime($firstPlanData->simple_date) < strtotime($this->startDate))
        {
          return FALSE;
        }
        elseif (strtotime($lastPlanData->simple_date) > strtotime($this->goalDate))
        {
          return FALSE;
        }

        return TRUE;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'There is existing data that falls outside of your plan date range. Either delete the data or adjust the date range.';
    }
}
