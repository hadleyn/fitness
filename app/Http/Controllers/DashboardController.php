<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

use App\Plan;
use App\Helpers\DateHelper;
use App\ReduceWeightPlan;
use App\ReduceFatPlan;
use App\GainMusclePlan;
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
			$viewData['dataForToday'][$p->id] = $p->getPlanDataOnSimpleDate(date('Y-m-d'));
		}
		Log::debug("data for today ".print_r($viewData['dataForToday'], TRUE));

		$viewData['plans'] = $plans;
		return view('dashboard.dashboard', $viewData);
	}

	public function newWeightReductionPlan()
	{
		$viewData['plan'] = new Plan;

		//Load up the new plan form
		return view('dashboard.weightreductionplanform', $viewData);
	}

	public function newFatReductionPlan()
	{
		$viewData['plan'] = new Plan;

		//Load up the new plan form
		return view('dashboard.fatreductionplanform', $viewData);
	}

	public function newMuscleGainPlan()
	{
		$viewData['plan'] = new Plan;

		//Load up the new plan form
		return view('dashboard.musclegainplanform', $viewData);
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
			'startingWeight' => 'required|numeric',
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
		$reduceWeightPlan->goal_weight = $request->planGoal;
		$reduceWeightPlan->starting_weight = $request->startingWeight;
		$reduceWeightPlan->save();

    $plan->user_id = Auth::id();
		$plan->name = $request->planName;
		$plan->start_date = date('Y-m-d H:i:s', strtotime($request->startDate));
		$plan->plannable_id = $reduceWeightPlan->id;
		$plan->plannable_type = 'App\ReduceWeightPlan';
    $plan->save();

		return redirect()->route('dashboard');
	}

	public function saveReduceFatPlan(Request $request)
	{
		Log::debug('running plan validation');
		$request->validate([
    	'planName' => 'required|max:100',
			'startingFatPercentage' => 'required|numeric|gt:0|lt:100',
			'startDate' => 'required|date',
			'goalDate' => 'required|date',
			'planGoal' => 'required|numeric|gt:0|lt:100',
			'planId' => new UserOwnsPlan
		]);

		Log::debug('made it past validation?');

		$plan = new Plan;
		$reduceFatPlan = new ReduceFatPlan;
		if (!empty($request->planId))
		{
			$plan = Plan::find($request->planId);
			$reduceFatPlan = $plan->plannable;
		}

		$reduceFatPlan->goal_date = date('Y-m-d H:i:s', strtotime($request->goalDate));
		$reduceFatPlan->goal_fat_percentage = $request->planGoal;
		$reduceFatPlan->starting_fat_percentage = $request->startingFatPercentage;
		$reduceFatPlan->save();

    $plan->user_id = Auth::id();
		$plan->name = $request->planName;
		$plan->start_date = date('Y-m-d H:i:s', strtotime($request->startDate));
		$plan->plannable_id = $reduceFatPlan->id;
		$plan->plannable_type = 'App\ReduceFatPlan';
    $plan->save();

		return redirect()->route('dashboard');
	}

	public function saveGainMusclePlan(Request $request)
	{
		$request->validate([
    	'planName' => 'required|max:100',
			'startingMusclePercentage' => 'required|numeric|gt:0|lt:100',
			'startDate' => 'required|date',
			'goalDate' => 'required|date',
			'planGoal' => 'required|numeric|gt:0|lt:100',
			'planId' => new UserOwnsPlan
		]);

		Log::debug('made it past validation?');

		$plan = new Plan;
		$reduceFatPlan = new GainMusclePlan;
		if (!empty($request->planId))
		{
			$plan = Plan::find($request->planId);
			$reduceFatPlan = $plan->plannable;
		}

		$reduceFatPlan->goal_date = date('Y-m-d H:i:s', strtotime($request->goalDate));
		$reduceFatPlan->goal_muscle_percentage = $request->planGoal;
		$reduceFatPlan->starting_muscle_percentage = $request->startingMusclePercentage;
		$reduceFatPlan->save();

    $plan->user_id = Auth::id();
		$plan->name = $request->planName;
		$plan->start_date = date('Y-m-d H:i:s', strtotime($request->startDate));
		$plan->plannable_id = $reduceFatPlan->id;
		$plan->plannable_type = 'App\GainMusclePlan';
    $plan->save();

		return redirect()->route('dashboard');
	}
}
