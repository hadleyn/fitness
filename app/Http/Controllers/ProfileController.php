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
      $tmp = $prefs->where('preference_name', UserPreference::LOCAL_TIMEZONE)->first();
      if ($tmp)
      {
        $viewData['timezone'] = $tmp->preference_value;
      }
      else
      {
        $viewData['timezone'] = 'America/New_York';
      }
      return view('profile.profile', $viewData);
    }

    public function saveUserPreferences(Request $request)
    {
      $request->validate([
      	'timezone' => 'required',
  		]);

      $up = UserPreference::where('user_id', Auth::id())->where('preference_name', UserPreference::LOCAL_TIMEZONE)->first();
      if (!$up)
      {
        $up = new UserPreference;
        $up->user_id = Auth::id();
        $up->preference_name = UserPreference::LOCAL_TIMEZONE;
      }
      $up->preference_value = $request->timezone;

      $up->save();

      return redirect()->route('profile');
    }
}
