

@extends('layouts.cover')
@section('title', 'Server Error | First Academy')
@section('content')

<div class=" p-3">
<div class='display-2 p-3 light'>500</div>
<h2>Server Error !  We are fixing it !</h2>
<a href="{{ url()->previous() }}" class="btn btn-outline-light mt-4">Go Back</a>
</div>
@endsection
