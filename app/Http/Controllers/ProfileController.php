<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\UserPreference;
use App\User;

class ProfileController extends Controller
{


    public function index()
    {
      //Get all preferences for this user
      $prefs = User::find(Auth::id())->userPreferences;
      $viewData['timezone'] = $prefs->where('preference_name', UserPreference::LOCAL_TIMEZONE)->first();
      $viewData['dst'] = $prefs->where('preference_name', UserPreference::DST)->first();
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

      $up = new UserPreference;
      $up->user_id = Auth::id();
      $up->preference_name = UserPreference::DST;
      if ($request->dst)
      {
        $up->preference_value = $request->dst;
      }
      else
      {
        $up->preference_value = 0;
      }
      $up->save();

      return redirect()->route('profile');
    }
}
