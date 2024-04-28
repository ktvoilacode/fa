

@extends('layouts.cover')
@section('title', '402 Error')
@section('content')

<div class=" p-3">
<div class='display-2 p-3 light'>402</div>
<h2>{{ $exception->getMessage() }}</h2>
<a href="{{ url('/register')}}" class="btn btn-outline-light mt-4">Go to Register</a>
</div>
@endsection
