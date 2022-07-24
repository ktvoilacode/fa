
@extends('layouts.app')
@section('title','Checkout - First Academy' )
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')
@section('content')



<div class="row">
  <div class="col-12 col-md-8">
<form method="post" action="{{ route('product.order')}}">
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="bg-white rounded">
<div class="card-body p-4 ">
@if(isset($product))
<h1><i class="fa fa-cart"></i> Checkout</h1><br>

<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">Product</th>
      <th scope="col">Price</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td scope="row" rowspan=1><b>{{strip_tags($product->name)}}</b><br>{!! $product->description !!}</td>
      <td>@if($product->price==0) - FREE - @else {{ $product->price}} @endif</td>
    </tr>
     <tr>
    </tr>
    <tr>

      <td scope="row" colspan=1>Total Amount</td>
      <td><span class="badge badge-warning" style="font-size: 20px"><i class="fa fa-rupee"></i> <span class="total">{{ $product->price }} </span></span></td>

      
    </tr>
   
  </tbody>
</table>

@if($product->price!=0)
<div class="card bg-light mb-3"> 
  <div class="card-body">
    <div class="form-check mb-2">
    

  <input class="form-check-input" type="radio" name="type" id="exampleRadios1" value="instamojo" checked> Pay Online
 
</div>

  </div>

</div>
 @endif
 <input class="form-check-input amount" type="hidden" name="txn_amount" value="{{ $product->price }}">
<input class="form-check-input product" type="hidden" name="product_id"  value="{{ $product->id }}">

<input class="form-check-input" type="hidden" name="details" value="{{ request()->get('details')}}">

@if(request()->get('test_id'))
<input class="form-check-input" type="hidden" name="test_id" value="{{ request()->get('test_id')}}">
@endif

<button class="btn btn-lg btn-primary" type="submit">Next</button>

@else

<h1><i class="fa fa-cart"></i> Checkout</h1><br>
<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">Test</th>
      <th scope="col">Price</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td scope="row" rowspan=1><b>{{$test->name}}</b><br>{!! $test->details !!}</td>
      <td>@if($test->price==0) - FREE - @else {{ $test->price}} @endif</td>
    </tr>
     <tr>
    </tr>
    <tr>
      <td scope="row" colspan=1>Total Amount</td>
      <td><span class="badge badge-warning" style="font-size: 20px"><i class="fa fa-rupee"></i> <span class="total">{{ $test->price }} </span></span></td>
    </tr>
  </tbody>
</table>

@if($test->price!=0)
<div class="card bg-light mb-3"> 
  <div class="card-body">
    <div class="form-check mb-2">
  <input class="form-check-input" type="radio" name="type" id="exampleRadios1" value="instamojo" checked> Pay Online
  </div>
  </div>
</div>
 @endif
 <input class="form-check-input amount" type="hidden" name="txn_amount" value="{{ $test->price }}">
<input class="form-check-input product" type="hidden" name="test_id"  value="{{ $test->id }}">
<button class="btn btn-lg btn-primary" type="submit">Next</button>
@endif
</div>		
</div>
</form>
</div>
<div class="col-12 col-md-4">
<form method="post" action="{{ route('product.order')}}">
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="bg-primary text-white rounded">
<div class="card-body p-4 ">
<h1><i class="fa fa-cart"></i> Access Code</h1><br>
<div class="mb-3">
  <input class="form-control" type="text" name="coupon" placeholder="Enter the Coupon Code">
 <input class="form-check-input amount" type="hidden" name="txn_amount" value="0">
 @if(isset($product))
<input class="form-check-input product" type="hidden" name="product_id"  value="{{ $product->id }}">
@else
<input class="form-check-input product" type="hidden" name="test_id"  value="{{ $test->id }}">
@endif
</div>
<input class="form-check-input amount" type="hidden" name="details" value="{{ request()->get('details')}}">
<button class="btn btn-sm btn-outline-light" type="submit">Submit</button>

</div>    
</div>
</form>
</div>

</div>
@endsection           