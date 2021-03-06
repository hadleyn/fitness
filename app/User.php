<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Plan;
use App\PlanData;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function plans()
    {
	     return $this->hasMany('App\Plan');
    }

    public function userPreferences()
    {
      return $this->hasMany('App\UserPreference');
    }

    public function doesUserOwnPlan($planId)
    {
      if (Plan::where('user_id', $this->id)
        ->where('id', $planId)
        ->count() === 1)
      {
        return TRUE;
      }
      return FALSE;
    }

    public function doesUserOwnPlanData($planDataId)
    {
      $planData = PlanData::find($planDataId);
      if (Plan::where('user_id', $this->id)
        ->where('id', $planData->plan_id)
        ->count() === 1)
      {
        return TRUE;
      }
      return FALSE;
    }
}
