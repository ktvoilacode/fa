@extends('layouts.login')
@section('title', 'Login | '.client('name'))

@section('content') 
<div class="container">
    <div class="row justify-content-center " >
        <div class="col-12 col-lg-8"> 
<div class="bg-white border rounded p-4 p-md-5">
<div class="row">
    <div class="col-12 col-md-6">
        <form class="form-signin " method="POST" action="{{ route('login') }}">

    @csrf

    @if($_SERVER['HTTP_HOST'] == 'project.test' || $_SERVER['HTTP_HOST'] == 'prep.firstacademy.in')
    <img class="mb-4 mt-3" src="{{ asset('images/logo.png') }}" alt="" width="250" >
@else
    <img class="" src="@if(request()->session()->get('client')) {{ request()->session()->get('client')->logo }} @else {{ asset('images/piofx.png') }} @endif" alt="Piofx" width="150" >
@endif

    
    <hr>
    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif
    <h1 class="h4 mb-3 mt-4 font-weight-normal">Please sign in</h1>
    <label for="inputEmail" class="sr-only">Email address</label>

    <input id="email" type="email" class="form-control mb-3 p-3 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email address" required autocomplete="email" autofocus>

    @error('email')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    <label for="inputPassword" class="sr-only">Password</label>

   

     <div class="form-group">
    <div class="input-group" id="show_hide_password">
      <input class="form-control" type="password"  name="password" placeholder="Password" required autocomplete="current-password">
      <div class="input-group-append">
      <div class="input-group-text">
        <a href="#"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
      </div>
  </div>
    </div>
  </div>

    @error('password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    <div class="checkbox mb-3 mt-3">
        <label>
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

            <label class="form-check-label" for="remember">
                {{ __('Remember Me') }}
            </label>
        </label>
    </div>
    <button class="btn btn-lg btn-primary " type="submit">Sign in</button>
   
    <a class="btn btn-link mt-2" href="{{ route('register') }}">
        Create an account &nbsp;
    </a>
    @if (Route::has('password.request'))
    <div>
        
    <a class="btn btn-link mt-2" href="{{ route('password.request') }}">
        {{ __('Forgot Your Password?') }}
    </a>
</div>
    @endif

</form>
    </div>
    <div class="col-12 col-md-6 ">
        <img src="{{ asset('images/general/signin-image.jpg')}}" class="mt-5 mt-md-3 p-3 w-100" />
    </div>

</div>
</div>
</div>
</div>
</div>
@endsection