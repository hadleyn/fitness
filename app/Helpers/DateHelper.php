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

  public static function localTimestamp($format = 'Y-m-d h:i:s a')
  {
    // $up = User::find(Auth::id())->userPreferences;
    //
    // $timezonePreference = $up->where('preference_name', UserPreference::LOCAL_TIMEZONE)->first();
    // return strtotime($timezonePreference->preference_value.' hours');

    $dt = new \DateTime("now", new \DateTimeZone('America/New_York'));

    return $dt->format($format);
  }

  public static function timezoneSelector($currentlySelected = '')
  {
    $regions = array(
      'Africa' => \DateTimeZone::AFRICA,
      'America' => \DateTimeZone::AMERICA,
      'Antarctica' => \DateTimeZone::ANTARCTICA,
      'Aisa' => \DateTimeZone::ASIA,
      'Atlantic' => \DateTimeZone::ATLANTIC,
      'Europe' => \DateTimeZone::EUROPE,
      'Indian' => \DateTimeZone::INDIAN,
      'Pacific' => \DateTimeZone::PACIFIC
    );
    $timezones = array();
    foreach ($regions as $name => $mask)
    {
      $zones = \DateTimeZone::listIdentifiers($mask);
      foreach($zones as $timezone)
      {
    		// Lets sample the time there right now
    		$time = new \DateTime(NULL, new \DateTimeZone($timezone));
    		// Us dumb Americans can't handle millitary time
    		$ampm = $time->format('H') > 12 ? ' ('. $time->format('g:i a'). ')' : '';
    		// Remove region name and add a sample time
    		$timezones[$name][$timezone] = substr($timezone, strlen($name) + 1) . ' - ' . $time->format('H:i') . $ampm;
    	}
    }

    $select = '';
    foreach($timezones as $region => $list)
    {
    	$select .= '<optgroup label="' . $region . '">' . "\n";
    	foreach($list as $timezone => $name)
    	{
        if ($timezone == $currentlySelected)
        {
          $select .= '<option selected="selected" value="' . $timezone . '">' . $name . '</option>' . "\n";
        }
        else
        {
    		    $select .= '<option value="' . $timezone . '">' . $name . '</option>' . "\n";
        }
    	}
    	$select .= '<optgroup>' . "\n";
    }

    return $select;
  }
}

?>
