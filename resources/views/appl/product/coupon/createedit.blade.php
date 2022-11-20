@extends('layouts.app')
@include('meta.createedit')
@section('content')

@include('flash::message')

  @if($stub=='Create')
      <form method="post" action="{{route($app->module.'.store')}}" enctype="multipart/form-data">
  @else
      <form method="post" action="{{route($app->module.'.update',$obj->id)}}" enctype="multipart/form-data">
  @endif  
  <div class="card">
    <div class="card-header bgblue" style="">
      <h3 class="py-2  mb-0">
        @if($stub=='Create')
          Create {{ $app->module }}
        @else
          Update {{ $app->module }}
        @endif  
       <button type="submit" class="btn btn-success  float-right">Save</button>
       </h3>
    </div>
    <div class="card-body">
     
     <div class="row">
      <div class="col-12 col-md-6">
        <div class="form-group">
        <label for="formGroupExampleInput ">{{ ucfirst($app->module)}} Code</label>
        <input type="text" class="form-control" name="code" id="formGroupExampleInput" 
            @if($stub=='Create')
            value="{{ (old('code')) ? old('code') : $obj->code }}"
            @else
            value = "{{ $obj->code }}"
            @endif
          >
      </div>
      </div>
      <div class="col-12 col-md-6">
      <div class="form-group">
        <label for="formGroupExampleInput ">Expiry</label>
        <input id="datetimepicker2" type="text" class="form-control" name="expiry" id="formGroupExampleInput" 
            @if($stub=='Create')
            value="{{ (old('expiry')) ? old('expiry') : $obj->expiry }}"
            @else
            value = "{{ $obj->expiry }}"
            @endif
          >
      </div>

      </div>
     </div>

     <div class="row">
      <div class="col-12 col-md-4">
         <div class="form-group">
        <label for="formGroupExampleInput ">Unlimited</label>
        <select class="form-control" name="unlimited">
          <option value="0" @if(isset($obj)) @if($obj->unlimited==0) selected @endif @endif >NO</option>
          <option value="1" @if(isset($obj)) @if($obj->unlimited==1) selected @endif @endif >YES</option>
        </select>
      </div>

      </div>
      <div class="col-12 col-md-4">
         <div class="form-group">
        <label for="formGroupExampleInput ">Only for Enrolled</label>
        <select class="form-control" name="enrolled">
          <option value="0" @if(isset($obj)) @if($obj->enrolled==0) selected @endif @endif >NO</option>
          <option value="1" @if(isset($obj)) @if($obj->enrolled==1) selected @endif @endif >YES</option>
        </select>
      </div>

      </div>
      <div class="col-12 col-md-4">
        <div class="form-group">
        <label for="formGroupExampleInput ">Client</label>
        <input  type="text" class="form-control" name="client_slug" id="formGroupExampleInput" value="{{ request()->get('client_slug')}}" disabled>
        <input  type="hidden" class="form-control" name="client_slug" id="formGroupExampleInput" value="{{ request()->get('client_slug')}}" >
      </div>
     </div>
      </div>
      

      

      

      <div class="form-group">
        <label for="formGroupExampleInput">Products</label>
         <div class=" card p-3 bg-light">
          <div class="row">
          @foreach($products as $product)
          @if($product->status==1)
          <div class="col-12 col-md-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="products[]" value="{{$product->id}}" id="defaultCheck1" @if($obj->products->contains($product->id))) checked @endif>
            <label class="form-check-label" for="defaultCheck1">
              {!! $product->name !!} 
            </label>
          </div>
          </div>
          @endif
          @endforeach
         </div>
         </div>
      </div>

      @if(subdomain()=='prep')
       <div class="form-group">
        <label for="formGroupExampleInput">Tests</label>
         <div class=" card p-3 bg-white">
          <div class="row">
          @foreach($tests as $product)
          @if($product->status==1)
          <div class="col-12 col-md-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tests[]" value="{{$product->id}}" id="defaultCheck1" @if($obj->tests->contains($product->id))) checked @endif>
            <label class="form-check-label" for="defaultCheck1">
              {!! $product->name !!} 
            </label>
          </div>
          </div>
          @endif
          @endforeach
         </div>
         </div>
      </div>
      @endif

      @if($stub=='Update')
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{ $obj->id }}">
      @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <button type="submit" class="btn btn-success btn-lg px-3">Save</button>    
    </div>
  </div>
</form>
@endsection