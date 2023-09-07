@extends('layouts.app')
@include('meta.createedit')
@section('content')

@include('flash::message')
  <div class="card">
    <div class="card-body">
      <h1 class="p-3 border bg-light mb-3">
          Assign Faculty 
       </h1>
      
      @if($stub=='Create')
      <form method="post" action="{{route($app->module.'.store')}}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{route('file.assign',$obj->id)}}" enctype="multipart/form-data">
      @endif  
      <div class="form-group">
        <label for="formGroupExampleInput ">File</label>
        <input type="text" class="form-control" name="name" value="{{ $obj->user->name}} - {{$obj->test->name}}" disabled 
          >
      </div>



      <div class="form-group">
        <label for="formGroupExampleInput ">Faculty</label>
        <select class="form-control" name="user_id">
          <option value="" @if(isset($obj)) @if(!$obj->status) selected @endif @endif > - None -</option>
          @foreach($users as $user)
           <option value="{{$user->id}}" @if(isset($writing)) @if($writing->user_id== $user->id) selected @endif @endif >{{$user->name}}</option>
          @endforeach
        </select>
      </div>
        
     

      @if($stub=='Update')
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="id" value="{{ $obj->id }}">
      @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <button type="submit" class="btn btn-info">Save</button>
    </form>
    </div>
  </div>
@endsection