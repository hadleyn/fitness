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
    <button class="btn btn-primary" id="toggleGraphView">Toggle to Table View</button>
  </div>

  <div class="chart-container row showing">
    <div class="col">
      <canvas id="dataChart" width="400" height="400"></canvas>
    </div>
  </div>
  <div class="table-container row">
    @yield('dataTable')
  </div>

  <div class="modal fade" id="dataPointEditModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Data Point</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="modalAlerts">

          </div>
          <p>You are editing a data point. You can provide a new value as well as a new date for this data point.</p>
          <form id="editDataPointForm">
            @csrf
            <input type="hidden" id="planDataId" name="planDataId" value="">
            <input type="hidden" name="planId" value="{{ $plan->id }}">
            <div class="row">
              <div class="col"><input class="form-control" type="text" name="editData" id="editData" placeholder="Edit Data Point"></div>
            </div>
            <div class="row">
              <div class="col"><input class="form-control" type="text" name="editDataDate" id="editDataDate" placeholder="Edit Data Date"></div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="dataPointEditSave" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
