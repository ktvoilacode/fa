
@extends('layouts.app')
@section('title','Purchase - First Academy' )
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')
@section('content')


@if($product)

<div class="bg-white">
<div class="card-body p-4 ">
<h1 class="text-primary"><i class="fa fa-times-circle"></i> Restricted Access</h1>
<hr>

<p>  The following test ({{$test->name}}) is a part of our premium product ({{$product->name}}).<hr>Kindly purchase the product to continue further. </p>

<a href="{{ route('product.view',$product->slug) }}">
<button class="btn btn-outline-primary btn-lg mt-3"> Buy Now</button>
</a>


@auth

	@if(\auth::user()->sms_token==1)
	<a href="{{ route('product.checkout-access',$product->slug) }}">
	<button class="btn btn-lg btn-outline-primary mt-3">Access Code</button>
	</a>
	@else
	<button type="button" class="btn btn-lg btn-outline-primary mt-3" type="button" data-toggle="modal" data-target="#exampleModal ">Access Code</button>
	@endif

@else
	<button type="button" class="btn btn-lg btn-outline-primary mt-3" type="button" data-toggle="modal" data-target="#exampleModal ">Access Code</button>
@endauth

</div>
</div>
@else
<div class="bg-white">
<div class="card-body p-4 ">
<h1 class="text-primary"><i class="fa fa-times-circle"></i> Restricted Access</h1>
<hr>

<p>  The following test ({{$test->name}}) is a part of our premium product.<hr>Kindly purchase the product to continue further. </p>

<a href="{{ route('test.view',$test->slug) }}">
<button class="btn btn-outline-primary btn-lg mt-3"> Buy Now</button>
</a>


@auth

	@if(\auth::user()->sms_token==1 && $product)
	<a href="{{ route('product.checkout-access',$product->slug) }}">
	<button class="btn btn-lg btn-outline-primary mt-3">Access Code</button>
	</a>
	@else
	<button type="button" class="btn btn-lg btn-outline-primary mt-3" type="button" data-toggle="modal" data-target="#exampleModal ">Access Code</button>
	@endif

@else
	<button type="button" class="btn btn-lg btn-outline-primary mt-3" type="button" data-toggle="modal" data-target="#exampleModal ">Access Code</button>
@endauth
</div>
</div>

@endif
@include('blocks.loginmodal')

@endsection