<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\UserPreference;
use App\User;

class ProfileController extends Controller
{


    public function index()
    {
      //Get all preferences for this user
      $prefs = User::find(Auth::id())->userPreferences;
      $viewData['timezone'] = $prefs->where('preference_name', UserPreference::LOCAL_TIMEZONE)->first()->preference_value;
      return view('profile.profile', $viewData);
    }

    public function saveUserPreferences(Request $request)
    {
      $request->validate([
      	'timezone' => 'required',
  		]);

      $up = new UserPreference;
      $up->user_id = Auth::id();
      $up->preference_name = UserPreference::LOCAL_TIMEZONE;
      $up->preference_value = $request->timezone;

      $up->save();

      return redirect()->route('profile');
    }
}
