<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Plan;
use App\Rules\DateOrderingRule;
use App\Rules\DataDateCollisionRule;

class SaveReduceWeightPlan extends FormRequest
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
          'startingWeight' => 'required|numeric',
          'startDate' => 'required|date',
          'goalDate' => 'required|date',
          'planGoal' => 'required|numeric',
          'startDate' => new DateOrderingRule($this->startDate, $this->goalDate),
          'startDate' => new DataDateCollisionRule($this->planId, $this->startDate, $this->goalDate)
        ];
    }
}
