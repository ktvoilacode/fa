
@extends('layouts.app')
@section('title','Coupon Code - First Academy' )
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')
@section('content')


<div class="card">
    <div class="card-title p-4 bg-light">
      <h4 class="mb-0"><i class="fa fa-cart"></i> Enter Coupon Code</h4>
    </div>
    <div class="card-body">
      
     


      <form method="get" action="{{ route('coupon.use')}}">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
       
       <div class="mb-3">
        <input class="form-control" type="text" name="code" placeholder="Enter the Coupon Code">
        
        
      </div>
      <button class="btn btn-lg btn-success" type="submit">Submit</button>
      </form>

    </div>
</div>

@endsection           