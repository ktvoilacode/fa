@extends('layouts.app')
@section('title', 'First Academy - The best practice tests for IELTS | OET and other tests')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')
@section('content')

@if( \auth::user()->activation_token!=1 )
<div class="rounded p-4 mb-4" style="background: #caf7dd; border:1px solid #39c072;">
  <h4 class="">Validate your account</h4>
  <p>Your account has not been validated yet. You are only a few steps away from complete access to our platform.</p>
  <a href="{{ route('activation')}}">
    <button class="btn btn-success">Validate Now</button>
  </a>
</div>
@endif

@include('flash::message')
<div class="">
  <div class="row no-gutters">

    <div class="col-12  col-md-8 col-lg-9">

      @if(auth::user()->orders()->where('status',1)->count()!=0)
      <div class="mb-4">
        <div class=" mb-4">
          <h3 class="mb-3">Featured Testpacks</h3>
          <div class="row no-gutters">
            <div class="col-6 col-lg-2 col-sm-4">
              <div class="rounded p-3 mx-1 mb-2" style="background:#ffffbd;border:1px solid #cfcf6c;">
                <img src="https://prep.firstacademy.in/storage/product/mJ1oYUANm6ivexhFN57G8J329ZjiIb2Pp8BEbp71.png" class="w-100 pb-3 rounded" />
                <div class="text-center"><b>IELTS MOCK - GENERAL (BASIC)</b></div>
              </div>
            </div>
            <div class="col-6 col-lg-2 col-sm-4">
              <div class="rounded p-3 mx-1 mb-2" style="background:#e4ffbd;border:1px solid #bfde92;">
                <img src="https://prep.firstacademy.in/storage/product/mzuE839XqkIHAZFbacXGHEg3AJV3fH7FoA7aRfcL.png" class="w-100 pb-3 rounded" />
                <div class="text-center"><b>10 DET PRACTICE TESTS </b></div>
              </div>
            </div>
            <div class="col-6 col-lg-2 col-sm-4">
              <div class="rounded p-3 mx-1 mb-2" style="background:#ffe3b7;border:1px solid #e1bc81;">
                <img src="https://prep.firstacademy.in/storage/product/BRslPPiLVS6AR7u7bl9SMhUp4bAofjNxfmHrQiua.png" class="w-100 pb-3 rounded" />
                <div class="text-center"><b>GRAMMATICAL RANGE</b></div>
              </div>
            </div>
            <div class="col-6 col-lg-2 col-sm-4">
              <div class="rounded p-3 mx-1 mb-2" style="background:#ffffbd;border:1px solid #cfcf6c;">
                <img src="https://prep.firstacademy.in/storage/product/0pfbypfWtISD3zj11A1AxrrMOOG1p99qvr0o2FiY.png" class="w-100 pb-3 rounded" />
                <div class="text-center"><b>GRE MINI <br> TEST</b></div>
              </div>
            </div>
            <div class="col-6 col-lg-2 col-sm-4">
              <div class="rounded p-2 mx-1 mb-2" style="background:#c7ffe4;border:1px solid #9fdcbc;">
                <img src="https://prep.firstacademy.in/storage/product/UlxANxiG1Sx5YC4Ne1XZye9yCEvzDR6zw4M2Fi11.png" class="w-100 pb-3 rounded" />
                <div class="text-center"><b>OET NURSING MOCK </b></div>
              </div>
            </div>
            <div class="col-6 col-lg-2 col-sm-4">
              <div class="rounded p-2 mx-1 mb-2" style="background:#fff4e8;border:1px solid #e7d8c8;">
                <img src="https://prep.firstacademy.in/storage/product/SLMdKGwKxf7zqL2oooRXeAmeQkosxu7p8v6shNLa.png" class="w-100 pb-3 rounded" />
                <div class="text-center"><b>ESSENTIAL VOCABULARY</b></div>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-white p-3 mb-4">
          <h3 class="mb-3">My Testpacks</h3>
          @include('appl.pages.blocks.productlist')
        </div>
        <div class="p-3 my-4 rounded" style="background:#d8f8d8;border:1px solid #90cf92; ">
          <h4>Dummy AD slot #one</h4>
          <button class="btn btn-success">Call to action</button>
        </div>
        <div class="bg-white p-3 mb-4">
          <h3 class="mb-3">My Tests</h3>
          @include('appl.pages.blocks.testlist')
        </div>

      </div>
      @endif




    </div>

    <div class="col-12  col-md-4 col-lg-3 d-block d-sm-none d-md-block">
      <div class="card mb-4 ml-2 ml-md-4">
        <div class="bg-image" style="background-image: url({{asset('images/bg/bg5.jpg')}})">
        </div>
        <div class="user_container">
          @if(\Storage::disk('public')->exists('images/'.\auth::user()->id.'.jpg'))
          <img src="{{ asset('storage/images/'.\auth::user()->id.'.jpg')}}" class="user img-thumbnail" style="" />
          @elseif(\Storage::disk('public')->exists('images/'.\auth::user()->id.'.jpeg'))
          <img src="{{ asset('storage/images/'.\auth::user()->id.'.jpeg')}}" class="user img-thumbnail" />
          @elseif(\Storage::disk('public')->exists('images/'.\auth::user()->id.'.png'))
          <img src="{{ asset('storage/images/'.\auth::user()->id.'.png')}}" class="user img-thumbnail" />
          @else
          <img src="{{ asset('images/admin/user.png')}}" class="user " />
          @endif
        </div>
        <div class="card-body pt-0 text-center mb-3">
          <div class="h4 mb-1 mt-4">Hi, {{ \auth::user()->name}}! </div>

          <div class="mb-3"><span class="badge badge-secondary">ID Number: @if(\auth::user()->idno){{ \auth::user()->idno}} @else - @endif</span> </div>
          <a href="{{ route('useredit')}}">
            <button class="btn btn-primary">Edit</button></a>
          <a href="{{ route('logout') }}" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
            <button class="btn btn-success">Logout</button>
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>

        </div>
      </div>
      <div class="card mb-4 ml-2 ml-md-4 p-0 text-white" style="background: #2a79b9;border:0px">
        <div class="p-4">
          <h4 class="">Use Coupon </h4>
          <p class="mb-0">To activate your test or product you can use the coupon code in the below link.</p>
        </div>
        <a href="{{ route('coupon.try')}}" class="btn btn-primary" style="border:0px">Activation</a>
      </div>
      <div class="p-3 ml-2 ml-md-4 my-4 rounded">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Modal AD</button>
      </div>
      <div class="p-3 ml-2 ml-md-4 my-4 rounded" style="background:#f8f4d8;border:1px solid #d0c88c; ">
        <h4>Dummy AD slot #two</h4>
        <button class="btn btn-primary">Call to action</button>
      </div>

      <div class="p-3 ml-2 ml-md-4 my-4 rounded" style="background:#f9e1e6;border:1px solid #e4c2c9; ">
        <h4>Dummy AD slot #three</h4>
        <button class="btn btn-danger">Call to action</button>
      </div>


    </div>

    <div class="col-12  col-md-4 col-lg-3 d-none d-sm-block d-md-none">
      <div class="card mb-4 ml-2 ml-md-4">
        <div class="bg-image" style="background-image: url({{asset('images/bg/bg5.jpg')}})">
        </div>
        <div class="card-body pt-0  mb-3">
          @if(\Storage::disk('public')->exists('images/'.\auth::user()->id.'.jpg'))
          <img src="{{ asset('storage/images/'.\auth::user()->id.'.jpg')}}" class="float-right" style="width:120px;margin:30px;margin-top: -50px;" />
          @elseif(\Storage::disk('public')->exists('images/'.\auth::user()->id.'.jpeg'))
          <img src="{{ asset('storage/images/'.\auth::user()->id.'.jpeg')}}" class="float-right" style="width:120px;margin:30px;margin-top: -50px;" />
          @elseif(\Storage::disk('public')->exists('images/'.\auth::user()->id.'.png'))

          <img src="{{ asset('storage/images/'.\auth::user()->id.'.png')}}" class="float-right" style="width:120px;margin:30px;margin-top: -50px;" />
          @else
          <img src="{{ asset('images/admin/user.png')}}" class="float-right" style="width:120px;margin:30px;margin-top: -50px;" />
          @endif
          <div class="h4 mb-4 mt-4">Hi, {{ \auth::user()->name}}! </div>

          <a href="{{ route('useredit')}}">
            <button class="btn btn-primary">Edit</button></a>
          <a href="{{ route('logout') }}" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
            <button class="btn btn-success">Logout</button>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </div>

      <div class="card mb-4 mr-2 mr-md-4 p-0 text-white" style="background: #2a79b9;border:0px">
        <div class="p-4">
          <h4 class="">Use Coupon </h4>
          <p class="mb-0">To activate your test or product you can use the coupon code in the below link.</p>
        </div>
        <a href="{{ route('coupon.try')}}" class="btn btn-primary" style="border:0px">Activation</a>
      </div>

    </div>


    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg bg-warning p-3 rounded ">
        <div class="modal-content bg-warning py-2" style="border:0px">
          <div class="h2">Dummy Ad slot #four</div>
          <p>For modals that simply appear rather than fade in to view, remove the .fade class from your modal markup.</p>
        </div>

        <button class="btn btn-danger ">Call to action</button>
      </div>
    </div>


  </div>
</div>
</div>
@endsection