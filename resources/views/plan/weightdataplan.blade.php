@extends('plan.planmain')

@section('addData')
<div class="row">
  <div class="col"><input class="form-control" type="text" name="data"  id="test" placeholder="Add a weight data point..."></div>
  <div class="col"><input type="submit" class="btn btn-primary" value="Add Data"></div>
</div>
@endsection

@section('dataTable')
<!-- <div class="col"> -->
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th scope="col">Date</th>
        <th scope="col">Weight</th>
        <th scope="col">Expected</th>
        <th scope="col">Daily Delta</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($continuousPlanData as $date => $pd)
      <tr>
        <th scope="row">{{ date($displayDateFormat, strtotime($date)) }}</th>
        @if ($pd == null)
          <td>No Data</td>
          <td>{{ $plan->plannable->getExpectedDataForDate($date) }}</td>
          <td>{{ $dailyDeltas[$date] }}</td>
          <td><a href="#" class="editDataPoint">Set Data?</a></td>
        @else
          <td>{{ $pd->data }}</td>
          <td>{{ $plan->plannable->getExpectedDataForDate($date) }}</td>
          @if (is_numeric($dailyDeltas[$date]) && $dailyDeltas[$date] > 0)
            <td class="table-warning">{{ $dailyDeltas[$date] }}</td>
          @else
            <td class="table-success">{{ $dailyDeltas[$date] }}</td>
          @endif
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
    Slope (weight lost per day): {{ round($slope, 3) }}
  </div>
  <div class="col">
    Expected Loss Per Day: {{ $plan->plannable->getExpectedLossPerDay() }}
  </div>
  <div class="col">
    Y-Intercept: {{ $yIntercept }}
  </div>
</div>
<div class="row">
  	<div class="col">
  		Total Weight Lost: {{ $plan->plannable->getTotalWeightLost() }}
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
