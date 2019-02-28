<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Plan;
use App\User;
use App\PlanType;

class DashboardController extends Controller
{

	public function index()
	{
		Log::debug('User id is '.Auth::id());
		$plans = User::find(Auth::id())->plans;
		$viewData['plans'] = $plans;
		return view('dashboard.dashboard', $viewData);
	}


	public function newPlan()
	{
		//Let's get a list of existing plan types
		$viewData['planTypes'] = PlanType::all();
		
		//Load up the new plan form
		return view('dashboard.newplanform');
	}

}
