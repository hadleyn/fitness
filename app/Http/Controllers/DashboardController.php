<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use App\Plan;
use App\ReduceWeightPlan;
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
		foreach ($plans as $p)
		{
			$viewData['completionDate'][$p->id] = $p->plannable->getPredictedCompletionDate();
		}

		$viewData['plans'] = $plans;
		return view('dashboard.dashboard', $viewData);
	}

	public function newWeightReductionPlan()
	{
		$viewData['plan'] = new Plan;

		//Load up the new plan form
		return view('dashboard.weightreductionplanform', $viewData);
	}

	public function editPlan($planId)
	{
		Log::debug('Editing plan with id '.$planId);
		//Does this user even own the plan they are trying to edit?
		$user = User::find(Auth::id());
		$plan = new Plan;
		// $errors = new MessageBag();
		if ($user->doesUserOwnPlan($planId))
		{
			Log::debug('Yes, user owns this plan');
			$plan = Plan::find($planId);
		}
		else
		{
			//Session flash error
    	// add your error messages:
    	// $errors->add('authentication_error', 'Invalid plan');
		}
		$viewData['plan'] = $plan;
		return view($plan->plannable->getPlanForm(), $viewData);
	}

	public function saveReduceWeightPlan(Request $request)
	{
		Log::debug('running plan validation');
		$request->validate([
    	'planName' => 'required|max:100',
			'startDate' => 'required|date',
			'goalDate' => 'required|date',
			'planGoal' => 'required|numeric',
			'planId' => new UserOwnsPlan
		]);

		Log::debug('made it past validation?');

		$plan = new Plan;
		$reduceWeightPlan = new ReduceWeightPlan;
		if (!empty($request->planId))
		{
			$plan = Plan::find($request->planId);
			$reduceWeightPlan = $plan->plannable;
		}

		$reduceWeightPlan->goal_date = date('Y-m-d H:i:s', strtotime($request->goalDate));
		$reduceWeightPlan->goal = $request->planGoal;
		$reduceWeightPlan->save();

    $plan->user_id = Auth::id();
		$plan->name = $request->planName;
		$plan->start_date = date('Y-m-d H:i:s', strtotime($request->startDate));
		$plan->plannable_id = $reduceWeightPlan->id;
		$plan->plannable_type = 'App\ReduceWeightPlan';
    $plan->save();

		return redirect()->route('dashboard');
	}

}
