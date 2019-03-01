@extends('Layouts.appmain')

@section('content')
<div class="container">
	<p>Yo dawg I heard you like dashboards</p>
	@if ($plans->count() === 0)
		<p>:( You don't have any plans set up</p>
		<a class="btn btn-primary" href="/dashboard/newplan">Create a Plan</a>
	@else
		<p>Here's a list of your plans</p>
		<ul class="list-group">
			@foreach ($plans as $p)
				<li class="list-group-item"><a href="/dashboard/editplan/{{ $p->id }}">{{ $p->name }}</a></li>
			@endforeach
		</ul>
		<a class="btn btn-primary" href="/dashboard/newplan">Create a Plan</a>
	@endif
</div>
@endsection
