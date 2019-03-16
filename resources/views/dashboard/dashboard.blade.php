@extends('Layouts.appmain')

@section('content')
<div class="container">
	<p>Yo dawg I heard you like dashboards test change</p>
	@if ($plans->count() === 0)
		<p>:( You don't have any plans set up</p>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newPlanChooserModal">
		  Create New Plan
		</button>
	@else
		<p>Here's a list of your plans</p>
		<ul class="list-group">
			@foreach ($plans as $p)
				<li class="list-group-item">
					<a href="/dashboard/editplan/{{ $p->id }}"><span data-feather="edit"></span></a>
					<a href="/plan/{{ $p->id }}">{{ $p->name }}</a>
					<form method="POST" action="/plan/addData">
						@csrf
						<input type="hidden" name="planId" value="{{ $p->id }}">
						<input type="text" name="data" placeholder="Data quick add...">
					</form>
					<span>Target Completion Date: {{ date('D M j, Y', strtotime($p->plannable->goal_date)) }}</span>
					<span>Projected Completion Date: {{ date('D M j, Y', strtotime($completionDate[$p->id])) }}</span>
					@if (strtotime($completionDate[$p->id]) <= strtotime($p->plannable->goal_date))
						<span class="badge badge-success">On Track!</span>
					@else
						<span class="badge badge-warning">Not on Track</span>
					@endif
				</li>
			@endforeach
		</ul>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newPlanChooserModal">
		  Create New Plan
		</button>
	@endif
	@include('dashboard.modals.newplanchooser')
</div>
@endsection
