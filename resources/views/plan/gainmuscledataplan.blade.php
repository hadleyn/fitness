@extends('plan.planmain')

@section('addData')
<div class="row">
  <div class="col"><input class="form-control" type="text" name="data" placeholder="Muscle Percentage Data Point..."></div>
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
        <th scope="col">Muscle Percentage</th>
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
        @if ($pd->data == null)
          <td>No Data</td>
          <td>{{ $plan->getExpectedDataForDate($pd->simple_date) }}</td>
          <td>{{ $dailyDeltas->get($index)->data }}</td>
          <td><a href="#" class="editDataPoint">Set Data?</a></td>
        @else
          <td>{{ $pd->data }}</td>
          <td>{{ $plan->getExpectedDataForDate($pd->simple_date) }}</td>
          <td>{{ $dailyDeltas->get($index)->data }}</td>
          <td><a href="#" class="editDataPoint" data-id="{{ $pd->id }}">Edit</a></td>
        @endif
      </tr>
      @endforeach
    </tbody>
  </table>
<!-- </div> -->
@endsection

@section('planAnalysis')
<div class="row">
  <div class="col">
    Slope (% muscle gained per day): {{ round($slope, 3) }}
  </div>
  <div class="col">
    Expected Loss Per Day: {{ $plan->getExpectedLossPerDay() }}
  </div>
  <div class="col">
    Y-Intercept: {{ $yIntercept }}
  </div>
</div>
<div class="row">
  	<div class="col">
  		Total Fat Lost: {{ $plan->plannable->getTotalMuscleGained() }}%
	</div>
	<!-- <div class="col">
    <div>
      <label>
        <input id="toggleRollingAverage" type="checkbox" data-toggle="toggle">
        Toggle Rolling Average
      </label>
    </div>
	</div> -->
</div>
@endsection
