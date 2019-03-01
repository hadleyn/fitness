@extends('Layouts.appmain')

@section('content')
<div class="container">
  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif
  <form method="POST" action="/dashboard/saveplan">
    @csrf
    @isset ($plan)
    <input type="hidden" value="{{ $plan->id }}" name="planId">
    @endisset

    <div class="form-group">
      @if (empty($plan->id))
      <p>Create your new plan!</p>
      @else
      <p>Edit your plan</p>
      @endif
      <label class="required" for="planName">Plan Name</label>
      <input type="text" class="form-control" id="planName" name="planName" placeholder="Enter a descriptive name for your new plan!" value="{{ old('planName', $plan->name) }}">
    </div>
    <div class="form-group">
      <label for="planType">Plan Type</label>
      <select id="planType" name="planType" class="custom-select">
          <option selected></option>
          @foreach ($planTypes as $planType)
            <option @if ($plan->plan_type_id == $planType->id) selected="selected" @endif value="{{ $planType->id }}">{{ $planType->description }}</option>
          @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="startDate">Start Date</label>
      <input type="text" class="form-control" id="startDate" name="startDate" placeholder="mm/dd/yyyy" value="{{ old('startDate', $plan->start_date) }}">
    </div>
    <div class="form-group">
      <label for="goalDate">Goal Date</label>
      <input type="text" class="form-control" id="goalDate" name="goalDate" placeholder="mm/dd/yyyy" value="{{ old('goalDate', $plan->goal_date) }}">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button><a href="/dashboard" class="btn btn-link">Cancel</a>
  </form>
</div>
@endsection
