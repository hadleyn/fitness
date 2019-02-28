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
        @foreach ($planTypes as $plan)
          <option value="{{ $plan->plan_id }}">{{ $plan->description }}</option>
        @endforeach
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
