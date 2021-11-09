@extends('layouts.login')
@section('title', 'Register | '.getenv('APP_NAME'))
@section('description', 'Don\'t have the First Academy account? Create one for FREE.')
@section('keywords', 'Register first academy, signup first academy, registration first academy')
@section('content')
<div class="container">
    <div class="row justify-content-center">
<div class="col-12 col-lg-10"> 
<div class="bg-white border rounded p-4 p-md-5">
<div class="row">
    <div class="col-12 col-md-6">
<form class=" " method="POST" action="{{ route('register') }}">
    @csrf

    <h1>Register</h1>
    <hr>
    <!--
    <div class="bg-light border rounded p-3 mb-3"><small class="text-left"> Note that verification of email or phone number is mandatory to attempt the free or premium tests. So kindly ensure that you enter the correct details.</small></div> -->
    <div class="form-group row">
        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

        <div class="col-md-8">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Enter your fullname" autofocus>

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

        <div class="col-md-8">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter you email (gmail preferred)" >

            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right">Phone Number</label>
        
        @if(isset($message)){{$message }}@endif
        <div class="col-md-8">
            <input id="phone" type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="Enter phone number">
        </div>

        @error('phone')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
       <!-- <small class="text-md-right col-md-12 mt-2 text-primary">Kindly enter phone number with international calling extension <br>(eg: For india +918888888888) </small>-->
    </div>
    <div class="form-group row">
        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

        <div class="col-md-8">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" name="password" required autocomplete="new-password">

            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="password-confirm" class="col-md-4 col-form-label text-md-right" >{{ __('Confirm Password') }}</label>

        <div class="col-md-8">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="re-enter password" required autocomplete="new-password">
        </div>
    </div>

    <div class="form-group row">
        <label for="code" class="col-md-4 col-form-label text-md-right">Coupon (optional)</label>

        <div class="col-md-8">
            <input id="code" type="text" class="form-control" placeholder="Enter Coupon Code " name="code" >
        </div>
    </div>


    <div class="form-group row text-md-left">
        <div class="col-md-4 col-form-label text-md-left">&nbsp;
        </div>
        <div class="col-md-8">
            <button type="submit" class="btn btn-primary">
                {{ __('Register') }}
            </button>
        </div>
    </div>
</form>
    </div>
    <div class="col-12 col-md-6 ">
        <img src="{{ asset('images/general/signup-image.jpg')}}" class="mt-5 mt-md-3 p-3 w-100" />
    </div>

</div>
</div>
</div>


    </div>
</div>
@endsection
