@extends('layouts.app')
@section('title', 'User Activation')
@section('content')

@include('flash::message')

<div class="alert alert-info alert-important " role="alert">
 Kindly note that activation of account is compulsory to attempt the tests. In case of any issue kindly write to us at <span class="text-primary">info@firstacademy.in</span> 
</div>

<div class="row">
	<div class="col-12 col-md-12">
		<div class="bg-white">

			<div class="card-body p-4 mb-4 mb-md-0">
				@if($user->activation_token==1)
				<h1 >Email Verified </h1>
				<p>Your email address ({{$user->email}}) is verified.</p>
				@else
				<h3  class="text-primary">Verify your email id</h3>
				<p>We have sent you an activation email, kindly click on the activation link to verify your email.</p>
				<p>Sometimes email may land up in SPAM FOLDER. Kindly check spam floder before resending.</p>
				<form method="post" action="{{ route('activation') }}" >

				</form>
				<a href="{{ route('activation') }}?resend_email=1">
					<button class="btn btn-warning">Resend activation email</button>
				</a>

				<div class="p-4 mt-4 border rounded bg-light">
				<h4>Update Email </h4>
				<form method="post" action="{{ route('activation') }}" >
					<input class="form-control mb-3" name="email" value="{{$user['email']}}"/>
					<input class="form-control mb-3" type="hidden" name="type" value="update_email"/>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<button class="btn btn-success" type="submit">Update</button>
				</form>
				</div>
				
				@endif
			</div>		
		</div>
	</div>
	<!--
	<div class="col-12 col-md-6">
		<div class="bg-white">
			<div class="card-body p-4 ">
				@if($user->sms_token==1)
				<h1 >Phone number Verified </h1>
				<p>Your phone number ({{$user->phone}}) is verified.</p>
				@else
				<h3 class="text-primary">Verify your Phone number</h3>
				<p>We have sent you a 4 digit activation code on your phone number. Kindly enter the code to verify your number.</p>
				<form method="post" action="{{ route('sms.verify') }}" >
					<input class="form-control mb-3" type="number" name="sms_code" placeholder="Enter verification code"/>
					<input class="form-control mb-3" type="hidden" name="type" value="otp"/>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<button class="btn btn-primary" type="submit">Verify</button>
					<a href="{{ route('activation') }}?resend_sms=1">
					<button class="btn btn-warning" type="button">Resend Code</button>
				</a>
				</form>
				<div class="p-4 mt-4 border rounded bg-light">
				<h4>Update Phone number</h4>
				<form method="post" action="{{ route('activation') }}" >
					<input class="form-control mb-3" name="number" value="{{$user['phone']}}"/>
					<input class="form-control mb-3" type="hidden" name="type" value="update_phone"/>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<button class="btn btn-success" type="submit">Update</button>
				</form>
				</div>

				<div class="alert alert-warning alert-important mt-3" role="alert">
  For international users, kindly send an email to <span class="text-success">info@firstacademy.in</span> to activate your account.
</div>
				@endif
			</div>		
		</div>
	</div>
	-->
</div>

@endsection           