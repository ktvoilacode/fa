@extends('layouts.app')
@include('meta.createedit')
@section('content')

@include('flash::message')
  <div class="card">
    <div class="card-body">

      @if($stub=='Create')
      <form method="post" action="{{route($app->module.'.store')}}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{route('userstore',$obj->id)}}" enctype="multipart/form-data">
      @endif 
      <h1 class="p-3 border bg-light mb-3">
        @if($stub=='Create')
          Create {{ $app->module }}
        @else
          Profile
        @endif  

        <button type="submit" class="btn btn-outline-success float-right">Save</button>
       </h1>
      
      

      <div class="row">
        <div class="col-12 col-md-6">

          <div class="form-group">
            <label for="formGroupExampleInput ">ID Number</label>
            <input type="text" class="form-control" name="idno" id="formGroupExampleInput" placeholder="" 
                @if($stub=='Create')
                value="{{ (old('idno')) ? old('idno') : '' }}"
                @else
                value = "{{ $obj->idno }}"
                @endif
              disabled>
          </div>

          <div class="form-group">
            <label for="formGroupExampleInput ">Email</label>
            <input type="text" class="form-control" name="email" id="formGroupExampleInput" placeholder="Enter the Email" 
                @if($stub=='Create')
                value="{{ (old('email')) ? old('email') : '' }}"
                @else
                value = "{{ $obj->email }}"
                @endif
              disabled>
          </div>

           <div class="form-group">
            <label for="formGroupExampleInput ">Password</label>
            <input type="password" class="form-control" name="password" id="formGroupExampleInput" placeholder="" 
                @if($stub=='Create')
                value="{{ (old('password')) ? old('password') : '' }}"
                @else
                value = ""
                @endif
              >
          </div>


        </div>
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="formGroupExampleInput "> Name</label>
            <input type="text" class="form-control" name="name" id="formGroupExampleInput" placeholder="Enter the Name" 
                @if($stub=='Create')
                value="{{ (old('name')) ? old('name') : '' }}"
                @else
                value = "{{ $obj->name }}"
                @endif
              >
          </div>

          <div class="form-group">
            <label for="formGroupExampleInput ">Phone</label>
            <input type="text" class="form-control" name="phone" id="formGroupExampleInput" placeholder="Enter the phone number" 
                @if($stub=='Create')
                value="{{ (old('phone')) ? old('phone') : '' }}"
                @else
                value = "{{ $obj->phone }}"
                @endif
              disabled>
          </div>

          <div class="form-group">
            <label for="formGroupExampleInput ">Re-type Password</label>
            <input type="password" class="form-control" name="repassword" id="formGroupExampleInput" placeholder="" 
                @if($stub=='Create')
                value="{{ (old('password')) ? old('password') : '' }}"
                @else
                value = ""
                @endif
              >
          </div>


        </div>
      </div>

      <div class='bg-light p-3 mb-3 d-none'>
        <div class="form-group">
        <label for="formGroupExampleInput ">Profile Pic</label>
        <input type="file" class="form-control bg-light" name="file" id="formGroupExampleInput" placeholder="Enter the image path" 
          >
      </div>
      </div>
      

     
       
      

      @if($stub=='Update')
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="id" value="{{ $obj->id }}">
      @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <button type="submit" class="btn btn-success btn-lg">Save</button>
    </form>
    </div>
  </div>
@endsection