

@extends('layouts.cover')
@section('title', '403 Error')
@section('content')

<div class=" p-3">
<div class='display-2 p-3 light'>403</div>
<h2>{{ $exception->getMessage() }}</h2>
<a href="{{ url()->previous() }}" class="btn btn-outline-light mt-4">Go to previous page</a>
</div>
@endsection
