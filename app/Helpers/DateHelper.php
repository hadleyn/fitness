<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

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
}

?>
