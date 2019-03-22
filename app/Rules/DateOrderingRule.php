<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DateOrderingRule implements Rule
{

    protected $startDate;
    protected $endDate;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->$endDate = $endDate;
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
        if (strtotime($this->startDate) > strtotime($this->endDate))
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
        return 'Start date must be before end date.';
    }
}
