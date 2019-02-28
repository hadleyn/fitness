@extends('Layouts.appmain')

@section('content')
<div class="container">
	<p>Yo dawg I heard you like dashboards</p>
	@if ($plans->count() === 0)
		<p>:( You don't have any plans set up</p>
	@else
		<p>Here's a list of your plans</p>
	@endif
</div>	
@endsection
