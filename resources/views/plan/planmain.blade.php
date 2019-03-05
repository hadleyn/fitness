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

  <div class="row">
    <div class="col">
      <form method="POST" action="/plan/addData">
        @csrf
        <input type="hidden" name="planId" id="planId" value="{{ $plan->id }}" />
        @yield('addData')
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <button class="btn btn-primary" id="toggleGraphView">Toggle to Table View</button>
    </div>
    <div class="col">
      <button class="btn btn-primary" id="bulkDataUpload">Bulk Data Upload</button>
    </div>
  </div>

  <div class="chart-container row showing">
    <div class="col">
      <canvas id="dataChart" width="400" height="400"></canvas>
    </div>
  </div>
  <div class="table-container row">
    @yield('dataTable')
  </div>

  <div class="row">
    <div class="col">
      <h2>Plan Analysis</h2>
    </div>
  </div>
  <div class="row">
    <div class="col">
      Slope (weight lost per day) 
    </div>
    <div class="col">

    </div>
    <div class="col">

    </div>
  </div>

  <!-- Modals -->
  @include('plan.modals.editdatapoint')
  @include('plan.modals.bulkupload')

</div>
@endsection
