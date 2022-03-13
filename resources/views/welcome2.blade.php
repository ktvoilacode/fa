@extends('layouts.first')
@section('title', 'First Academy - The best practice tests for IELTS | OET and other tests')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')

@section('content')  
<div class="bg" style=""> 
<div class="container ">
    <div class="row p-3 p-md-0">
        <div class="col-12 col-md-7">
            <div class="p-3 p-md-5"></div>
    <a class="navbar-brand " href="{{ url('/') }}">
            <img class="mb-2 mt-2" src="{{ asset('images/logo_white2.png') }}" alt="" style="max-width:250px;" >
        </a>
    <div class="heading text-light ">
    The time to be awesome has come
    </div>
    <div class="heading2 mb-5">
    Get started for FREE
    </div>


    

    <div class="p-5"></div>
    <div class="h4  mb-3" style="color:#a1d5e8">Explore our <a href="{{ route('tests')}}">
    <button class="btn btn-green btn-lg" style="">Tests</button>
    </a></div>
    <a href="{{ route('product.view','oet-mock-test-pack')}}">
    <button class="btn btn-outline-light btn-sm mb-2 mr-1">OET MOCK TEST PACK</button>
    </a>
    <a href="{{route('product.view','ielts-short-test')}}">
    <button class="btn btn-outline-light btn-sm mb-2 mr-1">IELTS Short  Test</button>
    </a>
    <a href="{{route('product.view','grammar-test')}}">
    <button class="btn btn-outline-light btn-sm mb-2 mr-1">Grammar Test</button>
    </a>
        </div>
        <div class="col-12 col-md-5">
             <div class="p-5 d-none d-md-block"><div class="p-3"></div></div>
             <img class="mb-2 mt-2 d-none d-md-block w-100" src="{{ asset('images/general/front5.png') }}" alt=""  >
        </div>
    </div>
    <div class="p-5"></div>
</div>
</div>
@endsection