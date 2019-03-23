<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Plan;

class DeletePlan extends FormRequest
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
      return $plan && $this->user()->can('delete', $plan);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'deleteConfirm' => 'required|regex:(DELETE)'
        ];
    }
}
