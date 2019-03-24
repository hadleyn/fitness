@extends('plan.planmain')

@section('addData')
<div class="row">
  @if ($dataForToday)
    <div class="col"><input class="form-control" type="text" name="data"  id="test" disabled="disabled" value="{{ $dataForToday->data }}"></div>
  @else
    <div class="col-8"><input class="form-control" type="text" name="data"  id="test" placeholder="Today's Fat Percentage Data Point..."></div>
    <div class="col-4"><input type="submit" class="btn btn-primary" value="Add Data"></div>
  @endif
</div>
@endsection

@section('dataChart')
<div class="chart-container row">
  <div class="col">
    <canvas id="dataChart" width="400" height="400"></canvas>
  </div>
</div>
<div class="chart-container row">
  <div class="col">
    <canvas id="dailyDeltaChart" width="400" height="400"></canvas>
  </div>
</div>
<div class="chart-container row">
	<div class="col">
		<canvas id="dailySlopeChart" width="400" height="400"></canvas>
	</div>
</div>
@endsection

@section('dataTable')
<!-- <div class="col"> -->
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th scope="col">Date</th>
        <th scope="col">Fat Percentage</th>
        <th scope="col">Expected</th>
        <th scope="col">Daily Delta</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($continuousPlanData as $index => $pd)
      @if ($pd->estimated)
      	<tr class="table-info">
      @else
      	<tr>
      @endif
        <th scope="row">{{ date($displayDateFormat, strtotime($pd->simple_date)) }}</th>
        <td>{{ $pd->data }}</td>
        <td>{{ $plan->getExpectedDataForDate($pd->simple_date) }}</td>
        <td>{{ $dailyDeltas->get($index)->data }}</td>
        @if ($pd->estimated)
          <td><a href="#" class="editDataPoint" data-id="{{ $pd->id }}" data-simpledate="{{ $pd->simple_date }}">Set Data?</a></td>
        @else
          <td><a href="#" class="editDataPoint" data-id="{{ $pd->id }}" data-simpledate="{{ $pd->simple_date }}">Edit</a></td>
        @endif
      </tr>
      @endforeach
    </tbody>
  </table>
<!-- </div> -->
@endsection

@section('planAnalysis')
<div class="row">
  <div class="col-3">
    <p>Slope (% fat lost per day):</p>
    <p>Expected Loss Per Day:</p>
    <p>Y-Intercept:</p>
    <p>Total Fat Lost:</p>
  </div>
  <div class="col-9 emphasis">
    <p>{{ round($slope, 3) }}</p>
    <p>{{ $plan->getExpectedLossPerDay() }}</p>
    <p>{{ $yIntercept }}</p>
  	<p>{{ $plan->plannable->getTotalFatLost() }}%</p>
  </div>
</div>
@endsection

@section('planOperations')
<a href="#" id="deletePlan">Delete Plan</a>
@endsection
