@extends('Layouts.appmain')

@section('content')
<div class="container">
  <h1>{{ $plan->name }}</h1>

  @if (count($continuousPlanData) === 0)
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
    <div class="col">
      <button class="btn btn-primary" id="bulkDataUpload">Bulk Data Upload</button>
    </div>
  </div>

  <nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <a class="nav-item nav-link active" id="nav-charts-tab" data-toggle="tab" href="#charts" role="tab" aria-controls="nav-charts" aria-selected="true">Charts</a>
      <a class="nav-item nav-link" id="nav-tables-tab" data-toggle="tab" href="#tables" role="tab" aria-controls="nav-tables" aria-selected="false">Tables</a>
      <a class="nav-item nav-link" id="nav-analysis-tab" data-toggle="tab" href="#analysis" role="tab" aria-controls="nav-analysis" aria-selected="false">Analysis</a>
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="charts" role="tabpanel" aria-labelledby="nav-charts-tab">
        @yield('dataChart')
    </div>
    <div class="tab-pane fade" id="tables" role="tabpanel" aria-labelledby="nav-tables-tab">
      <div class="table-container row">
        @yield('dataTable')
      </div>
    </div>
    <div class="tab-pane fade" id="analysis" role="tabpanel" aria-labelledby="nav-analysis-tab">
      @yield('planAnalysis')
    </div>
  </div>

  <!-- Modals -->
  @include('plan.modals.editdatapoint')
  @include('plan.modals.bulkupload')

</div>
@endsection
