@extends('layouts.first')
@section('title', 'Online Assessments for all')
@section('description', '')
@section('keywords', '')

@section('content')  
<div class="" style="background: #f0f9ff"> 
<div class="container ">
    <div class="row p-3 p-md-0">
        <div class="col-12 col-md-7">
            <div class="p-3 p-md-5"></div>
    <a class="navbar-brand " href="{{ url('/') }}">
            <img class="mb-2 mt-2" src="{{ asset('exam.svg') }}" alt="" style="max-width:250px;" >
        </a>
    <div class="heading  my-3" style="color:#e04c49">
    The time to be awesome has come
    </div>
    <div class="heading2 mb-5">
    Assessments for all!
    </div>


   

        @if(\auth::user())
            <a href="/home" class="btn btn-success btn-lg">Open Dashboard</a>
        @else
            <a href="/login" class="btn btn-primary btn-lg">Login</a>
            <a href="/register" class="btn btn-outline-dark btn-lg">Register</a>
        @endif
        </div>
       
    </div>
    <div class="p-5"></div>
</div>
</div>
@endsection