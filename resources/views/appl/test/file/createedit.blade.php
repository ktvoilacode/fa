@extends('layouts.app')
@include('meta.createedit')
@section('content')

@include('flash::message')
  <div class="card">
    <div class="card-body">
      <h1 class="p-3 border bg-light mb-3">
        @if($stub=='Create')
          Create {{ $app->module }}
        @else
          Update {{ $app->module }}
        @endif  
       </h1>
      
      @if($stub=='Create')
      <form method="post" action="{{route($app->module.'.store')}}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{route($app->module.'.update',$obj->id)}}" enctype="multipart/form-data">
      @endif  
      <div class="form-group">
        <label for="formGroupExampleInput ">File</label>
        @if($obj->user)
        <input type="text" class="form-control" name="name" value="{{ $obj->user->name}} - {{$obj->test->name}}" disabled 
          >
          @else
<input type="text" class="form-control" name="name" value="{{ $obj->session_id}} - {{$obj->test->name}}" disabled 
          >
          @endif
      </div>
      

      

      <div class="form-group">
        <label for="formGroupExampleInput ">Expert Feedback</label>
        <textarea class="form-control summernote" name="answer"  rows="5">
            @if($stub=='Create')
            {{ (old('answer')) ? old('answer') : '' }}
            @else
            {{ $obj->answer }}
            @endif
        </textarea>
      </div>

      <div class="form-group">
        <label for="formGroupExampleInput ">Score</label>
        <input type="text" class="form-control" name="score" value="{{ $obj->score}}">
      </div>

       <div class="form-group">
        <label for="formGroupExampleInput ">PDF Doc</label>
        <input type="file" class="form-control" name="file" id="formGroupExampleInput" placeholder="Enter the pdf path" 
          >
      </div>
      
     

      @if($stub=='Update')
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{ $obj->id }}">
      @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <button type="submit" class="btn btn-info">Save</button>
    </form>
    </div>
  </div>
@endsection