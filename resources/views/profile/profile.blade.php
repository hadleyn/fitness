@extends('Layouts.appmain')

<div class="container">
  <h2>Your Profile</h2>
  <p>Current Date Time: {{ DateHelper::localTimestamp() }}</p>
  <form method="post" action="/profile/saveUserPreferences">
    @csrf
    <div class="form-group">
      <label class="required" for="timezonePicker">Select your local timezone</label>
      <select class="form-control" id="timezonePicker" name="timezone">
        {!! DateHelper::timezoneSelector($timezone) !!}
      </select>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary">Save</button><a href="/dashboard" class="btn btn-link">Cancel</a>
    </div>
  </form>
</div>
