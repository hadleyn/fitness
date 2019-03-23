<?php

namespace App\Policies;

use App\User;
use App\Plan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Determine if the given plan can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Plan  $plan
     * @return bool
     */
    public function update(User $user, Plan $plan)
    {
        return $user->id === $plan->user_id;
    }


    /**
     * Determine if the given plan can be deleted by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Plan  $plan
     * @return bool
     */
    public function delete(User $user, Plan $plan)
    {
        return $user->id === $plan->user_id;
    }
}
