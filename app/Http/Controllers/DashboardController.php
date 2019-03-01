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
		return view('dashboard.newplanform', $viewData);
	}

	public function saveNewPlan(Request $request)
	{
		Log::debug("Validating the request");
		//Validate the incoming data
		$request->validate([
    	'planName' => 'required|max:100',
    	'planType' => 'required',
			'startDate' => 'required|date'
		]);

		Log::debug("We got past the validation.");

		//We're good!
		$plan = new Plan;

    $plan->user_id = Auth::id();
		$plan->name = $request->planName;
		$plan->type = $request->planType;
		$plan->start_date = $request->startDate;
		$plan->goal_date = $request->goalDate;

    $plan->save();
	}

}
