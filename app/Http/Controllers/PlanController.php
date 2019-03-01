<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Plan;
use App\PlanData;
use App\User;

class PlanController extends BehindLoginController
{
    public function index($planId)
    {
      Log::debug('Viewing a plan '.$planId);
      //Get all the associated data for this plan

      $plan = Plan::find($planId);
      $planData = $plan->planData;

      $viewData['plan'] = $plan;
      $viewData['planData'] = $planData;
      
      return view('plan.planmain', $viewData);
    }
}
