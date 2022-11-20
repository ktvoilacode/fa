@extends('layouts.app')
@section('content')

@if($user->phone)
<div class="bg-white">
	<div class="card-body p-4 ">
		<h1 class="text-primary"><i class="fa fa-check-circle"></i> Email & Whatsapp Notify</h1>
		<hr>
		@if($time)
		<p> Test review notification email is scheduled for {{$time}}:30 to {{$user->name }} ({{$user->email}})  </p>
		@else
		<p> Test review notification email is sent to {{$user->name }} ({{$user->email}}) & whatsapp to {{$user->phone}} </p>
		@endif
		<hr>
		<a href="{{ url()->previous() }}">
			<button class="btn btn-outline-primary btn-sm"> Back</button>
		</a>
	</div>
</div>
@else
<div class="bg-white">
	<div class="card-body p-4 ">
		<h1 class="text-primary"><i class="fa fa-check-circle"></i> Email  Notify</h1>
		<hr>
		@if($time)
		<p> Test review notification email is scheduled for {{$time}}:30 to {{$user->name }} ({{$user->email}})  </p>
		@else
		<p> Test review notification email is sent to {{$user->name }} ({{$user->email}})  </p>
		@endif
		<hr>
		<a href="{{ url()->previous() }}">
			<button class="btn btn-outline-primary btn-sm"> Back</button>
		</a>
	</div>
</div>

@endif
@endsection