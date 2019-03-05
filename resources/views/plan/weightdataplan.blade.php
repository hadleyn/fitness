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
