@extends('layouts.breadcrumb')

@section('title',strip_tags($obj->name).' - First Academy' )
@section('description', strip_tags($obj->description))
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET, '.$obj->name)

@section('content')
<div class="p-2 pt-4 pb-4 p-md-4 " style="background:#f3fbff;">
  @include('appl.product.product.blocks.header')
</div>

<div class="bg-white">
<main class="py-4 container ">

@if(count($obj->tests)!=0)

  <div class="row ">
    <div class="col-12 col-md-3">
      @include('appl.product.product.blocks.sideblock')
    
    </div>


    <div class="col-12 col-md-9">

        @include('appl.product.product.blocks.tests')
        @include('appl.product.product.blocks.mocks')
        @if(count($obj->tests)>3)
          
          @include('appl.product.product.blocks.viewmore')
  
        @endif


        @include('appl.product.product.blocks.why_these_tests')
          @include('appl.product.product.blocks.details')
          @if(subdomain()=='prep')
          @include('appl.product.product.blocks.related')
          @endif
    </div>
  </div>

@elseif(count($obj->mocks)!=0)

  <div class="row ">
    <div class="col-12 col-md-3">
      @include('appl.product.product.blocks.sideblock')
    
    </div>


    <div class="col-12 col-md-9">
        @include('appl.product.product.blocks.mocks')
    </div>
  </div>
@else

@include('appl.product.product.blocks.sideblock')

@endif
</main>
</div>
@endsection