<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use App\Plan;
use App\User;
use App\PlanType;
use App\Rules\UserOwnsPlan;

class DashboardController extends BehindLoginController
{

	public function index()
	{
		Log::debug('User id is '.Auth::id());
		$plans = User::find(Auth::id())->plans;
		//Calculate plan expected completion dates
		// foreach ($plans as $p)
		// {
		// 	switch ($p->planType->id)
		// 	{
		// 			case PlanType::REDUCE_WEIGHT:
		// 			case PlanType::GAIN_WEIGHT:
		// 			case PlanType::REDUCE_FAT_PERCENTAGE:
		// 			case PlanType::GAIN_MUSCLE:
		// 				$viewData['completionDate'][$p->id] = $p->getPredictedCompletionDate();
		// 				break;
		// 			default:
		// 				$viewData['completionDate'][$p->id] = "Not Applicable";
		// 	}
		// }
		Log::debug('Plans that I got '.print_r($plans, TRUE));
		$viewData['plans'] = $plans;
		return view('dashboard.dashboard', $viewData);
	}


	public function newPlan()
	{
		//Let's get a list of existing plan types
		$viewData['planTypes'] = PlanType::all();
		$viewData['plan'] = new Plan;

		//Load up the new plan form
		return view('dashboard.newplanform', $viewData);
	}

	public function editPlan($planId)
	{
		Log::debug('Editing plan with id '.$planId);
		//Does this user even own the plan they are trying to edit?
		$user = User::find(Auth::id());
		$viewData['planTypes'] = PlanType::all();
		$viewData['plan'] = new Plan;
		$errors = new MessageBag();
		if ($user->doesUserOwnPlan($planId))
		{
			Log::debug('Yes, user owns this plan');
			$viewData['plan'] = Plan::find($planId);
		}
		else
		{
			//Session flash error
    	// add your error messages:
    	$errors->add('authentication_error', 'Invalid plan');
		}

		return view('dashboard.newplanform', $viewData)->withErrors($errors);
	}

	public function savePlan(Request $request)
	{
		$this->validatePlan($request);

		Log::debug("We got past the validation.");

		//We're good!
		$plan = new Plan;
		if (!empty($request->planId))
		{
			$plan = Plan::find($request->planId);
		}

    $plan->user_id = Auth::id();
		$plan->name = $request->planName;
		$plan->plan_type_id = $request->planType;
		$plan->start_date = date('Y-m-d H:i:s', strtotime($request->startDate));
		$plan->goal_date = date('Y-m-d H:i:s', strtotime($request->goalDate));
		$plan->goal = $request->planGoal;

    $plan->save();

		return redirect()->route('dashboard');
	}

	private function validatePlan(Request $request)
	{
		Log::debug("Validating the request");
		//Validate the incoming data
		$request->validate([
    	'planName' => 'required|max:100',
    	'planType' => 'required',
			'startDate' => 'required|date',
			'goalDate' => 'required|date',
			'planGoal' => 'required',
			'planId' => new UserOwnsPlan
		]);
	}

}
