@extends('layouts.login')
@section('title', 'Register | '.client('name'))
@section('content')
<div class="container">
    <div class="row justify-content-center">
<div class="col-12 col-lg-8"> 
<div class="bg-white border rounded p-3 ">
<div class="">
    <div class=" ">
<form class=" " method="POST" action="{{ route('register') }}">
    @csrf

    @if(client('image_register'))
     <img src="{{ Storage::disk('s3')->url(client('image_register'))}}" class="w-100 mb-3" />
    @endif

     @if(request()->session()->get('config'))
            @if(request()->session()->get('config')->message_r)
              <div class="alert alert-warning alert-important mt-3">
                <div class=" h5 mt-1">{{request()->session()->get('config')->message_r}}</div>
                @if(request()->session()->get('config')->timer_r)
                 <p id="d" class="my-2 text-danger blink countdown_timer" data-timer="{{request()->session()->get('config')->timer_r}}"></p>
                @endif
              </div>
            @endif
          @endif

    <h1>Register</h1>
    <hr>
    @include('flash::message')
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
            <input type="hidden" name="client_slug" value="{{client('slug')}}" />
        </div>
    </div>

    @if(subdomain()=='prep')
    <div class="form-group row">
        <label for="code" class="col-md-4 col-form-label text-md-right">Coupon (optional)</label>
        <div class="col-md-8">
            <input id="code" type="text" class="form-control" placeholder="Enter Coupon Code" name="code">
        </div>
    </div>
    @elseif(client('default_coupon'))
    <input id="code" type="hidden" class="form-control" placeholder="Enter Coupon Code" name="code" value="{{client('default_coupon')}}">
        
    @endif

    @if(client('rform'))
        {!! client('rform') !!}
    @endif

    <div class="form-group row text-md-left">
        <div class="col-md-4 col-form-label text-md-left">&nbsp;
        </div>
        <div class="col-md-8">
            <button type="submit" class="btn btn-primary">
                {{ __('Register') }}
            </button><br><br><br>
            <a href="/" >back to homepage</a>
        </div>
    </div>

    </form>
    </div>


</div>
</div>
</div>


    </div>
</div>
@endsection
