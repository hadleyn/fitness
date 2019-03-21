<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\UserPreference;
use App\User;

class CheckUserPreferences
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $user = User::find(Auth::id());
      if ($user)
      {
        $tmp = $user->userPreferences->where('preference_name', UserPreference::LOCAL_TIMEZONE)->first();
        if (!$tmp)
        {
          $request->session()->flash('preferenceNotice', 'Time zone preference has not been set. <a href="/profile">Set now</a> to avoid weirdness with data entry!');
        }
      }
      return $next($request);
    }
}
