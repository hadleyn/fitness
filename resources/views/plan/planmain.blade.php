@extends('Layouts.appmain')

@section('content')
<div class="container">
  <h1>{{ $plan->name }} <span class="badge badge-primary">{{ $plan->planType->description }}</span></h1>

	@if (count($planData) === 0)
  <div class="alert alert-warning">
      <p>Looks like you don't have any data on this plan yet. Let's add some!</p>
  </div>
  @endif

  <form method="POST" action="/plan/addData">
    @csrf
    <div class="row">
      <div class="col"><input class="form-control" type="text" placeholder="Data A"></div>
      <div class="col"><input class="form-control" type="text" placeholder="Data B"></div>
      <div class="col"><input class="form-control" type="text" placeholder="Data C"></div>
    </div>
  </form>
</div>
@endsection
