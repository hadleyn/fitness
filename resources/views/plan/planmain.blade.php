@extends('Layouts.appmain')

@section('content')
<div class="container">
  <h1>{{ $plan->name }} <span class="badge badge-primary">{{ $plan->planType->description }}</span></h1>

	@if (count($planData) === 0)
  <div class="alert alert-warning">
      <p>Looks like you don't have any data on this plan yet. Let's add some!</p>
  </div>
  @endif

  @if (Session::has('status'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <span>{{ session('status') }}</span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif

  <form method="POST" action="/plan/addData">
    @csrf
    <input type="hidden" name="planId" id="planId" value="{{ $plan->id }}" />
    @yield('addData')
  </form>

  <div class="chart-container">
    <canvas id="dataChart" width="400" height="400"></canvas>
  </div>
</div>
@endsection
