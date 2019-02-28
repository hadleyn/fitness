@extends('Layouts.appmain')

@section('title', 'Hello World')

@section('content')
    <p>This is my body content. {{$fromcontroller}}</p>
@endsection