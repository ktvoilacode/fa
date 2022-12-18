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
    <div class="card-body">
      <h1 class="p-3 border bg-light mb-3">
        @if($stub=='Create')
          Create Test Pack
        @else
          Update Test Pack
        @endif  
        <button type="submit" class="btn btn-primary btn-lg float-right">Save</button>
       </h1>

      
      

      <div class="row">
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Test Pack Name</label>
            <input type="text" class="form-control" name="name" id="formGroupExampleInput" placeholder="Enter the Name" 
                @if($stub=='Create')
                value="{{ (old('name')) ? old('name') : '' }}"
                @else
                value = "{{ $obj->name }}"
                @endif
              >
          </div>
        </div>
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Slug</label>
            <input type="text" class="form-control" name="slug" id="formGroupExampleInput" placeholder="Enter the unique identifier" 
                @if($stub=='Create')
                value="{{ (old('slug')) ? old('slug') : '' }}"
                @else
                value = "{{ $obj->slug }}"
                @endif
              >
          </div>
        </div>
        <div class="col-12 col-md-3">
          <div class="form-group">
              <label for="formGroupExampleInput ">Client</label>
              <select class="form-control" name="client_slug">
                <option value="prep" @if(isset($obj)) @if($obj->client_slug==="prep") selected @endif @endif>Prep</option>
                @foreach($clients as $client)
                <option value="{{$client->slug}}" @if(isset($obj)) @if($obj->client_slug===$client->slug) selected @endif @endif >{{$client->slug}}</option>
                @endforeach
              </select>
            </div>
        </div>
        <div class="col-12 col-md-3">
           <div class="form-group">
            <label for="formGroupExampleInput ">Price</label>
            <input type="text" class="form-control" name="price" id="formGroupExampleInput" placeholder="Enter the price" 
                @if($stub=='Create')
                value="{{ (old('price')) ? old('price') : '' }}"
                @else
                value = "{{ $obj->price }}"
                @endif
              >
          </div>
        </div>
        
      </div>
      <div class="row">
        
        <div class="col-12 col-md-3">
           <div class="form-group">
            <label for="formGroupExampleInput ">Tags (seperated by commas)</label>
            <input type="text" class="form-control" name="tags" id="formGroupExampleInput" placeholder="Enter the price" 
                @if($stub=='Create')
                value="{{ (old('tags')) ? old('tags') : '' }}"
                @else
                value = "@if(isset($settings->tags)) {{ $settings->tags }} @endif"
                @endif
              >
          </div>
        </div>
        <div class="col-12 col-md-3">
           <div class="form-group">
            <label for="formGroupExampleInput ">Validity (months)</label>
            <input type="text" class="form-control" name="validity" id="formGroupExampleInput"  
                @if($stub=='Create')
                value="{{ (old('validity')) ? old('validity') : '6' }}"
                @else
                value = "{{ $obj->validity }}"
                @endif
              >
          </div>
        </div>
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Image</label>
            <input type="file" class="form-control" name="file" id="formGroupExampleInput" placeholder="Enter the image path" 
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
      
     

      <div class="row">
        <div class="col-12 col-md-6">
           <div class="form-group">
        <label for="formGroupExampleInput ">Description (visible on product listing & product page)</label>
        <textarea class="form-control summernote" name="description"  rows="5">
            @if($stub=='Create')
            {{ (old('description')) ? old('description') : '' }}
            @else
            {{ $obj->description }}
            @endif
        </textarea>
      </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="formGroupExampleInput ">Details (visible only on product page)</label>
            <textarea class="form-control summernote" name="details"  rows="5">
                @if($stub=='Create')
                {{ (old('details')) ? old('details') : '' }}
                @else
                {{ $obj->details }}
                @endif
            </textarea>
          </div>
        </div>
        
      </div>
      

    
      <div class="form-group">
        <label for="formGroupExampleInput">Mocks</label>
         <div class=" card p-3">
          <div class="row">
          @foreach($mocks as $mock)
          @if($mock->status==1)
          <div class="col-12 col-md-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="mocks[]" value="{{$mock->id}}" id="defaultCheck1" @if($obj->mocks->contains($mock->id))) checked @endif>
            <label class="form-check-label" for="defaultCheck1">
              {{ $mock->name }} ({{ $mock->slug }})
            </label>
          </div>
          </div>
          @endif
          @endforeach
         </div>
         </div>
      </div>
      
      <div class="form-group">
        <label for="formGroupExampleInput">Tests</label>
         <div class=" card p-3">
          <div class="row">
          @foreach($tests as $test)
          @if($test->status==1)
          <div class="col-12 col-md-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tests[]" value="{{$test->id}}" id="defaultCheck1" @if($obj->tests->contains($test->id))) checked @endif>
            <label class="form-check-label" for="defaultCheck1">
              {{ $test->name }} ({{ $test->slug }})
            </label>
          </div>
          </div>
          @endif
          @endforeach
         </div>
         </div>
      </div>
      
     

     
      

      @if($stub=='Update')
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{ $obj->id }}">
      @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <button type="submit" class="btn btn-primary btn-lg">Save</button>

    </div>
  </div>
      </form>
@endsection