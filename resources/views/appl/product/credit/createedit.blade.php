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
          Add Credits 
        @else
          Update Credits
        @endif  
       <button type="submit" class="btn btn-success  float-right">Save</button>
       </h3>
    </div>
    <div class="card-body">
     
     <div class="row">
      <div class="col-12 col-md-6">
        <div class="form-group">
        <label for="formGroupExampleInput ">{{ ucfirst($app->module)}} </label>
        <input type="text" class="form-control" name="credit" id="formGroupExampleInput" 
            @if($stub=='Create')
            value="{{ (old('credit')) ? old('code') : $obj->credit }}"
            @else
            value = "{{ $obj->credit }}"
            @endif
          >
      </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="form-group">
        <label for="formGroupExampleInput ">Client</label>
        <input  type="text" class="form-control" name="client_slug" id="formGroupExampleInput" value="{{ request()->get('client_slug')}}" disabled>
        <input  type="hidden" class="form-control" name="client_slug" id="formGroupExampleInput" value="{{ request()->get('client_slug')}}" >

         <input type="hidden" name="payment_mode" value="admin">
         <input type="hidden" name="user_id" value="{{ \auth::user()->id }}">
         <input type="hidden" name="details" value="added credits">
         <input type="hidden" name="status" value="1">
      </div>

      </div>
     </div>

   
      

      

      

     

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