<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Plan;
use App\User;

class PlanController extends Controller
{
    public function index($planId)
    {
      Log::debug('Viewing a plan '.$planId);
    }
}
