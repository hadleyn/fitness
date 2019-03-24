<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Plan;
use App\Rules\DateOrderingRule;
use App\Rules\DataDateCollisionRule;

class SaveReduceFatPlan extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      $plan = Plan::find($this->planId);
      if (!$plan)
      {
        return TRUE; //No plan exists, so this user can obviously create it
      }
      return $plan && $this->user()->can('update', $plan);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        	'planName' => 'required|max:100',
    			'startingFatPercentage' => 'required|numeric|gt:0|lt:100',
    			'startDate' => ['required',
                          'date',
                          new DateOrderingRule($this->startDate, $this->goalDate),
                          new DataDateCollisionRule($this->planId, $this->startDate, $this->goalDate)
                        ],
    			'goalDate' => 'required|date',
    			'planGoal' => 'required|numeric|gt:0|lt:100'
        ];
    }
}
