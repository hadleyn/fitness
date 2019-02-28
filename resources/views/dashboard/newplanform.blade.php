@extends('Layouts.appmain')

@section('content')
<div class="container">
<form>
  <div class="form-group">
    <p>Create your new plan!</p>
    <label for="planName">Plan Name</label>
    <input type="text" class="form-control" id="planName" placeholder="Enter a descriptive name for your new plan!">
  </div>
  <div class="form-group">
    <label for="planType">Plan Type</label>
    <select id="planType" class="custom-select">
        <option selected></option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
    </select>
  </div>
  <div class="form-group">
    <label for="startDate">Start Date</label>
    <input type="text" class="form-control" id="startDate" placeholder="mm/dd/yyyy">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button><a href="/dashboard" class="btn btn-link">Cancel</a>
</form>
</div>
@endsection
