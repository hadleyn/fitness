@extends('Layouts.appmain')

@section('content')
<div class="container">
	<p>Yo dawg I heard you like dashboards</p>
	@if ($plans->count() === 0)
		<p>:( You don't have any plans set up</p>
		<a class="btn btn-primary" href="/dashboard/newplan">Create a Plan</a>
	@else
		<p>Here's a list of your plans</p>
		<a class="btn btn-primary" href="/dashboard/newplan">Create a Plan</a>
	@endif
</div>	
@endsection
