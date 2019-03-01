<?php

namespace App\Rules;

use Illuminate\Support\Facades\Auth;

use App\User;

use Illuminate\Contracts\Validation\Rule;

class UserOwnsPlan implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
      if (empty($value))
      {
        return TRUE;
      }
      else
      {
        $user = User::find(Auth::id());
        return $user->doesUserOwnPlan($value);
      }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The plan Id is invalid';
    }
}
