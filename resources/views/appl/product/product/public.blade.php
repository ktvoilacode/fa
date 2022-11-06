@extends('layouts.breadcrumb')
@section('title', 'Tests - '.client('name'))

@section('content')
@include('flash::message')
<div  class=" ">
  <div class="">
    <div class="" style="background: #f3fbff">
    @include('appl.product.product.blocks.header_products')
    </div>

    <div class="bg-white">
      <div class="container pt-4">
        <div class="row">
           <div class="col-12 col-md-2">
             <div class=" border rounded bg-info text-light mb-4">
      <h5 class="mb-0 p-3">Tags</h5>
    <div class="list-group ">
    <a href="{{ route('product.public') }}" class="list-group-item list-group-item-action list-group-item-info @if(!request()->get('tag'))active @endif">
      All Products
    </a>

    @foreach($ptags as $tg=>$pt)
    <a href="{{ route('product.public') }}?tag={{  $tg}}" class="list-group-item list-group-item-action list-group-item-info @if(request()->get('tag')==$tg)active @endif">
      {{ strtoupper($tg) }}
    </a>
    @endforeach
    
    </div>
  </div>
           </div>
          <div class="col-12 col-md-10">
        <div id="search-items" class="row ">
         @include('appl.'.$app->app.'.'.$app->module.'.public_list')
       </div>
     </div>
        </div> 
       <br>

     </div>
   </div>
 </div>
</div>
@endsection


