<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\UserPreference;
use App\User;

class DateHelper
{
  /**
  * Format formats a date in mixed format into any PHP supported format
  *
  * @return String
  */
  public static function format($date, $format)
  {
    return date($format, strtotime($date));
  }

  public static function localTimestamp()
  {
    $up = User::find(Auth::id())->userPreferences;

    $timezonePreference = $up->where('preference_name', UserPreference::LOCAL_TIMEZONE)->first();
    return strtotime($timezonePreference->preference_value.' hours');
  }
}

?>
