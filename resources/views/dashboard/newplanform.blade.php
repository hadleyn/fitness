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
  <form method="POST" action="/dashboard/savenewplan">
    @csrf
    <div class="form-group">
      <p>Create your new plan!</p>
      <label class="required" for="planName">Plan Name</label>
      <input type="text" class="form-control" id="planName" name="planName" placeholder="Enter a descriptive name for your new plan!" value="{{ old('planName') }}">
    </div>
    <div class="form-group">
      <label for="planType">Plan Type</label>
      <select id="planType" name="planType" class="custom-select">
          <option selected></option>
          @foreach ($planTypes as $planType)
            <option value="{{ $planType->plan_type_id }}">{{ $planType->description }}</option>
          @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="startDate">Start Date</label>
      <input type="text" class="form-control" id="startDate" name="startDate" placeholder="mm/dd/yyyy" value="{{ old('startDate') }}">
    </div>
    <div class="form-group">
      <label for="goalDate">Goal Date</label>
      <input type="text" class="form-control" id="goalDate" name="goalDate" placeholder="mm/dd/yyyy" value="{{ old('goalDate') }}">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button><a href="/dashboard" class="btn btn-link">Cancel</a>
  </form>
</div>
@endsection
