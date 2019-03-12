<?php

namespace App;
/*
* There are many plan types available, but they all need to do be able to respond
* to certain calls. All plans must implement IPlan
*/

interface IPlan {

  public function getPlanForm();

  public function getPlanView();

  public function getPlanTypeDescription();

  public function getDataPointType();

  public function getDataPointUnit();

  public function getStartingValue();

  public function getPredictedCompletionDate();

  public function getExpectedLossPerDay();

  public function getExpectedLossData();

  public function getExpectedDataForDate($date);

  public function validateData(\Illuminate\Http\Request $request);

}

?>
