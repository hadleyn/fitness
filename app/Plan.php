<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function planType()
	{
		return $this->belongsTo('App\PlanType');
	}

	public function planData()
	{
		 return $this->hasMany('App\PlanData');
	}

}
