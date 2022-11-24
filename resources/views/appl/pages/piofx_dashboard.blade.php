@extends('layouts.app')
@section('title', 'Dashboard - '.request()->session()->get('client')->name)
@section('content')
 
@include('flash::message')
<div class="row">
  <div class="col-12 col-md-9">
    <!-- Banner-->
    @if( \auth::user()->activation_token!=1 )
    <div class="rounded p-3 mb-4" style="background: #caf7dd; border:1px solid #39c072;"><h4 class="">Validate your account</h4>
    <p>Your account has not been validated yet. You are only a few steps away from complete access to our platform.</p>
    <a href="{{ route('activation')}}">
    <button class="btn btn-success">Validate Now</button>
    </a>
    </div>
    @endif
    
    @if(client('message_l') || client('timer_l') || client('image_login'))
    <div class="bg-white p-3 rounded " style="border-top: 2px solid #bcd1e1">
      @if(client('image_dashboard'))
        <img src="{{\Storage::disk('s3')->url(client('image_dashboard'))}}" class="w-100 mb-3"/>
      @endif
      @if(client('message_l'))
        <div>{{client('message_l')}}</div>
      @endif
      @if(client('timer_d'))
                 <p id="d" class="h3 my-2 text-danger blink countdown_timer" data-timer="{{client('timer_d')}}"></p>
                
      @endif
    </div>
    @endif
    <!-- Tests -->
    <div class="bg-white p-3 rounded my-3" style="border-top: 2px solid #bcd1e1">
      <h3><i class="fa fa-check-square-o"></i> My Tests</h3>
      @if(count($products))
      <div class="table-responsive mb-0 pb-0">
      <table class="table table-bordered mb-0 pb-0">
        <thead>
          <tr class="bg-light">
            <th scope="col">#</th>
            <th scope="col">Tests </th>
            <th scope="col">Valid Till</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody class="{{$i=1}}">
          @foreach($products as $k=>$product)
        <tr>
            <th scope="row">{{($i++)}}</th>
            <td><a href="{{ route('product.view',$product->slug)}}">{{ strip_tags($product->name)}}</a></td>
            <td>{{ date('d M Y', strtotime($product_expiry[$product->id]))}}</td>
            <td>
              {{ $product_status[$product->id]}}
            </td>
            <td>
            <a href="{{ route('product.view',$product->slug)}}">
              <button class="btn  btn-sm btn-success">view</button>
            </a>
          </td>
          </tr>   
        @endforeach
        </tbody>
      </table>
      </div>
      @else
      <div class="card">
        <div class="card-body">
          - No tests assigned -
        </div>
      </div>
      @endif
    </div>
  </div>
  <div class="col-12 col-md-3">
    

    <div class="card mb-4 text-white" style="background: #2a79b9;border:0px">
      <div class="p-3">
        <h4 class="">Use Coupon </h4>
        <p class="mb-0">To activate your test, you can use the coupon code in the below link.</p>
      </div>
      <a href="{{ route('coupon.try')}}" class="btn btn-primary" style="border:0px">Activation</a>
    </div>

  </div>
</div>





@endsection
