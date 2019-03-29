<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Plan;

class DataDataCollisionRule implements Rule
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
        $lastPlanData = $plan->planData->sortBy('simple_date')->last();
        if (strtotime($firstPlanData->simple_date) < strtotime($this->startDate))
        {
          return FALSE;
        }
        elseif (strtotime($lastPlanData) > strtotime($this->goalDate))
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
        return 'Your ';
    }
}
