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
      <div class="row">
      <div class="col-12 col-md-6">
      <div class="form-group">
        <label for="formGroupExampleInput ">{{ ucfirst($app->module)}} Name</label>
        <input type="text" class="form-control" name="name" id="formGroupExampleInput" 
            @if($stub=='Create')
            value="{{ (old('name')) ? old('name') : $obj->name }}"
            @else
            value = "{{ $obj->name }}"
            @endif
          >
      </div>
      </div>
      <div class="col-12 col-md-6">
      <div class="form-group">
        <label for="formGroupExampleInput ">{{ ucfirst($app->module)}} Slug</label>
        <input type="text" class="form-control" name="slug" id="formGroupExampleInput" 
            @if($stub=='Create')
            value="{{ (old('slug')) ? old('slug') : $obj->slug }}"
            @else
            value = "{{ $obj->slug }}"
            @endif
          >
      </div>
      </div>

      </div>

      <div class="form-group">
        <label for="formGroupExampleInput ">Description</label>
<textarea class="form-control summernote" name="description"  rows="3">@if($stub=='Create'){{ (old('description')) ? old('description') : '' }} @else{{ $obj->description }}
            @endif
        </textarea>
      </div>

      <div class="row">
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Test 1</label>
            <input type="text" class="form-control" name="t1" id="formGroupExampleInput" 
                @if($stub=='Create')
                value="{{ (old('t1')) ? old('t1') : $obj->t1 }}"
                @else
                value = "{{ $obj->t1 }}"
                @endif
              >
          </div>
        </div>
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Test 2</label>
            <input type="text" class="form-control" name="t2" id="formGroupExampleInput" 
                @if($stub=='Create')
                value="{{ (old('t2')) ? old('t2') : $obj->t2 }}"
                @else
                value = "{{ $obj->t2 }}"
                @endif
              >
          </div>
        </div>
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Test 3</label>
            <input type="text" class="form-control" name="t3" id="formGroupExampleInput" 
                @if($stub=='Create')
                value="{{ (old('t3')) ? old('t3') : $obj->t3 }}"
                @else
                value = "{{ $obj->t3 }}"
                @endif
              >
          </div>
        </div>
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Test 4</label>
            <input type="text" class="form-control" name="t4" id="formGroupExampleInput" 
                @if($stub=='Create')
                value="{{ (old('t4')) ? old('t4') : $obj->t4 }}"
                @else
                value = "{{ $obj->t4 }}"
                @endif
              >
          </div>
        </div>
      </div>

       
      
      <div class="row">
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Report</label>
            <select class="form-control" name="noreport">
              <option value="2" @if(isset($settings->noreport)) @if($settings->noreport==1) selected @endif @endif >only score</option>
              <option value="0" @if(isset($settings->noreport)) @if($settings->noreport==0) selected @endif @endif >Show responses & answers (default)</option>
              <option value="1" @if(isset($settings->noreport)) @if($settings->noreport==1) selected @endif @endif >no-report</option>
            </select>
          </div>
        </div>
      <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Activation</label>
            <input type="text" id="datetimepicker"  class="form-control" name="activation" id="formGroupExampleInput"  
                @if($stub=='Create')
                value="{{ (old('activation')) ? old('activation') : '' }}"
                @else
                  @if(isset( $settings->activation))
                    value = "{{ $settings->activation }}"
                  @endif
                @endif
              >
          </div>
        </div>

        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">De-activation</label>
            <input type="text" id="datetimepicker2" class="form-control" name="deactivation" id="formGroupExampleInput"  
                @if($stub=='Create')
                value="{{ (old('deactivation')) ? old('deactivation') : '' }}"
                @else
                  @if(isset( $settings->deactivation))
                    value = "{{ $settings->deactivation }}"
                  @endif
                @endif
              >
          </div>
        </div>

        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Status</label>
            <select class="form-control" name="status">
              <option value="0" @if(isset($obj)) @if($obj->status==0) selected @endif @endif >Inactive</option>
              <option value="1" @if(isset($obj)) @if($obj->status==1) selected @endif @endif >Active</option>
            </select>
          </div>
        </div>
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