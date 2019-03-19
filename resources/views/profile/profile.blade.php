@extends('Layouts.appmain')

<div class="container">
  <h2>Your Profile</h2>
  <form method="post" action="/profile/saveUserPreferences">
    @csrf
    <div class="form-group">
      <label class="required" for="timezonePicker">Select your local timezone</label>
      @include('profile.timezonepicker')
    </div>
    <div class="form-group">
      <div class="form-check form-check-inline">
        <input class="form-check-input" name="dst" type="checkbox" id="dst" value="1">
        <label class="form-check-label" for="dst">DST</label>
      </div>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary">Save</button><a href="/dashboard" class="btn btn-link">Cancel</a>
    </div>
  </form>
</div>
