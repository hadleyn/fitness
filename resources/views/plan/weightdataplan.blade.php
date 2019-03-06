@extends('plan.planmain')

@section('addData')
<div class="row">
  <div class="col"><input class="form-control" type="text" name="data"  id="test" placeholder="Add a weight data point..."></div>
  <div class="col"><input type="submit" class="btn btn-primary" value="Add Data"></div>
</div>
@endsection

@section('dataTable')
<div class="col">
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Date</th>
        <th scope="col">Weight</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($planData as $pd)
      <tr>
        <th scope="row">{{ $pd->created_at }}</th>
        <td>{{ $pd->data }}</td>
        <td><a href="#" class="editDataPoint" data-id="{{ $pd->id }}">Edit</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection

@section('planAnalysis')
<div class="row">
  <div class="col">
    <h2>Plan Analysis</h2>
  </div>
</div>
<div class="row">
  <div class="col">
    Slope (weight lost per day): {{ round($plan->getSlope(), 3) }}
  </div>
  <div class="col">
    Expected Loss Per Day: {{ $plan->plannable->getExpectedLossPerDay() }}
  </div>
  <div class="col">
    Y-Intercept: {{ $plan->getYIntercept() }}
  </div>
</div>
<div class="row">
  	<div class="col">
  		Total Weight Lost: {{ $plan->plannable->getTotalWeightLost() }}	
	</div>
	<div class="col">
		<button class="btn btn-primary" id="toggleRollingAverage">Toggle Rolling Average</button>
	</div>
</div>
@endsection
