

@extends('layouts.cover')
@section('title', '404 Error')
@section('content')

<div class=" p-3">
<div class='display-2 p-3 light'>404</div>
<h2>{{ $exception->getMessage() }}</h2>
<a href="{{ url('/')}}" class="btn btn-outline-light mt-4">Go to Homepage</a>
</div>
@endsection
