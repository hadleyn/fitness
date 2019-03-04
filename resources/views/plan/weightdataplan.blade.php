@extends('plan.planmain')

@section('addData')
<div class="row">
  <div class="col"><input class="form-control" type="text" name="data"  id="test" placeholder="Add a weight data point..."></div>
  <div class="col"><input type="submit" class="btn btn-primary" value="Add Data"></div>
</div>
@endsection
