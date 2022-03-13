@extends('layouts.front')
@section('title', 'First Academy - The best practice tests for IELTS | OET and other tests')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')

@section('content')  
<div class="bg" style=""> 
<div class="container ">
    <div class="row p-3 p-md-0">
        <div class="col-12 col-md-8">
            <div class="p-3 p-md-3"></div>
    <div class="heading  text-light ">
    The time to be awesome<br> has come
    </div>
    <div class="heading2  mb-5">
    Get started for FREE
    </div>

    
   <div class="p-4"></div>
     </div>
        <div class="col-12 col-md-4">
             <img class="mb-2 mt-2 d-none d-md-block w-100 p-4" src="{{ asset('images/general/front5.png') }}" alt=""  >
        </div>
    </div>
</div>
</div>

@include('appl.home.tests')
@endsection