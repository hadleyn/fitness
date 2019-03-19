<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{

  const LOCAL_TIMEZONE = 'LOCAL_TIMEZONE';
  const DST = 'DST';

  public function user()
  {
    return $this->belongsTo('App\User');
  }
}
