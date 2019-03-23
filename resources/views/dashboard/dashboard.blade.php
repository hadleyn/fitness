@extends('Layouts.appmain')

@section('pageSpecificJS')
<script src="{{ URL::asset('js/dashboard.js?t='.time()) }}"></script>
@endsection

@section('content')
<div class="container">
	@if (Session::has('status'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <span>{{ session('status') }}</span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
	<div class="row">
		<div class="col-2">
			<h2>Dashboard</h2>
		</div>
		<div class="col-10">
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newPlanChooserModal">
			  Create New Plan
			</button>
		</div>
	</div>
	@if ($plans->count() === 0)
		<p><span data-feather="frown"></span> You don't have any plans set up. Now's a great time to get started!</p>
	@else
		<ul class="list-group">
			@foreach ($plans as $p)
				<li class="list-group-item">
					<div class="row">
						<div class="col-3">
							<a href="/dashboard/editplan/{{ $p->id }}"><span data-feather="edit"></span></a>
							<a href="/plan/{{ $p->id }}">{{ $p->name }}</a>
						</div>
						<div class="col-3">
							<form method="POST" action="/plan/addData">
								@csrf
								<input type="hidden" name="planId" value="{{ $p->id }}">
								@if ($dataForToday[$p->id])
									<input type="text" name="data" disabled="disabled" value="{{ $dataForToday[$p->id]->data }}">
								@else
									<input type="text" name="data" placeholder="Data quick add...">
								@endif
							</form>
						</div>
						<div class="col-6">
							<div class="row">
								<div class="col">
									<p>Target Completion Date: <p>
										@if (strtotime($completionDate[$p->id]))
											<p>Projected Completion Date:</p>
										@endif
								</div>
								<div class="col">
									<p class="emphasis">{{ date('D M j, Y', strtotime($p->plannable->goal_date)) }}</p>
									@if (strtotime($completionDate[$p->id]))
										<p class="emphasis">{{ date('D M j, Y', strtotime($completionDate[$p->id])) }}</p>
										@if (strtotime($completionDate[$p->id]) <= strtotime($p->plannable->goal_date))
											<p class="badge badge-success">On Track!</p>
										@else
											<p class="badge badge-warning">Not on Track</p>
										@endif
									@else
										<p class="badge badge-danger">Will Never Meet Goal</p>
									@endif
							</div>
						</div>
					</div>
				</li>
			@endforeach
		</ul>
	@endif
	@include('dashboard.modals.newplanchooser')
</div>
@endsection
