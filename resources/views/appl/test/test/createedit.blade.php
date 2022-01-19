@extends('layouts.app')
@section('title', 'Test Form | First Academy')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')
@section('content')

@include('flash::message')
  <div class="card">
    <div class="card-body">
      @if($stub=='Create')
      <form method="post" action="{{route($app->module.'.store')}}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{route($app->module.'.update',$obj->id)}}" enctype="multipart/form-data">
      @endif  

      <h1 class="p-3 border bg-light mb-3">
        @if($stub=='Create')
          Create {{ ucfirst($app->module) }}
        @else
          Update {{ ucfirst($app->module) }}
        @endif  

        <button type="submit" class="btn btn-primary btn-lg float-right">Save</button>
       </h1>
     
      


      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="formGroupExampleInput ">{{ ucfirst($app->module)}} Name</label>
            <input type="text" class="form-control" name="name" id="formGroupExampleInput" placeholder="Enter the Name" 
                @if($stub=='Create')
                value="{{ (old('name')) ? old('name') : '' }}"
                @else
                value = "{{ $obj->name }}"
                @endif
              >
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="formGroupExampleInput ">Slug <i class="fa fa-info-circle" data-toggle="tooltip" title="A slug is the part of a URL which identifies a particular page on a website in an easy to read form. Example - https://example.com/slug"></i></label>
            <input type="text" class="form-control" name="slug" id="formGroupExampleInput" placeholder="Enter the unique identifier" 
                @if($stub=='Create')
                value="{{ (old('slug')) ? old('slug') : '' }}"
                @else
                value = "{{ $obj->slug }}"
                @endif
              >
          </div>
        </div>
      </div>

      
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
        <label for="formGroupExampleInput" class="mb-0">Details</label>
        <small id="" class="form-text text-muted mb-3">Information to be displayed on individual test page (200 words with SEO keywords) </small>
        <textarea class="form-control summernote" name="details"  rows="5">
            @if($stub=='Create')
            {{ (old('details')) ? old('details') : '' }}
            @else
            {{ $obj->details }}
            @endif
        </textarea>
      </div>
      
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group">
        <label for="formGroupExampleInput " class="mb-0">Instructions (optional)</label>
        <small id="" class="form-text text-muted mb-3">Empty instructions will skip the instruction screen</small>
        <textarea class="form-control summernote" name="instructions"  rows="5">
            @if($stub=='Create')
            {{ (old('instructions')) ? old('instructions') : '' }}
            @else
            {{ $obj->instructions }}
            @endif
        </textarea>
      </div>
        </div>
      </div>

      

      

      @if(strtolower(request()->get('type'))=='writing' || !request()->get('type'))
      <div class="form-group bg-light p-4 border">
        <label for="formGroupExampleInput "><h4>Writing Question</h4></label>
        <textarea class="form-control summernote" name="description"  rows="5">
            @if($stub=='Create')
            {{ (old('description')) ? old('description') : '' }}
            @else
            {{ $obj->description }}
            @endif
        </textarea>
        <div class="mt-3">Enter the writing question with html formating. Leaving the question blank will allow the user to enter his custom question.</div>
      </div>
      @endif

       @if(strtolower(request()->get('type'))=='speaking' || !request()->get('type'))

      <div class="form-group bg-light p-4 border">
        <label for="formGroupExampleInput "><h4>Speaking Question</h4></label>
        <textarea class="form-control summernote" name="description"  rows="5">
            @if($stub=='Create')
            {{ (old('description')) ? old('description') : '' }}
            @else
            {{ $obj->description }}
            @endif
        </textarea>
      </div>
      @endif

      @if(strtolower(request()->get('type'))=='listening' || !request()->get('type'))
      <div class="form-group">
        <label for="formGroupExampleInput ">Audio File</label>
        <input type="file" class="form-control" name="file_" id="formGroupExampleInput" 
          >
      </div>
      @endif

      <div class="form-group">
        <label for="formGroupExampleInput ">Image</label>
        <input type="file" class="form-control" name="image_" id="formGroupExampleInput" placeholder="Enter the image path" 
          >
      </div>

      <div class="row">
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Category  </label>
            <select class="form-control" name="category_id">

              @foreach($categories as $category)
              <option value="{{$category->id}}" @if(isset($obj)) @if(strtolower($category->name)==strtolower(request()->get('category'))) selected  @elseif($obj->category_id == $category->id) selected @endif @endif >{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Type</label>
            <select class="form-control" name="type_id">
              @foreach($types as $type)
              <option value="{{$type->id}}" @if(isset($obj)) @if(strtoupper($type->name)==strtoupper(request()->get('type'))) selected @elseif($obj->type_id == $type->id) selected @endif @endif >{{ $type->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
         <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Marks</label>
            <input type="text" class="form-control" name="marks" id="formGroupExampleInput" placeholder="Enter the total marks" 
                @if($stub=='Create')
                value="{{ (old('marks')) ? old('marks') : '' }}"
                @else
                value = "{{ $obj->marks }}"
                @endif
              >
          </div>
        </div>
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Test Time (minutes)</label>
            <input type="text" class="form-control" name="test_time" id="formGroupExampleInput" placeholder="Enter the total time in minutes" 
                @if($stub=='Create')
                value="{{ (old('test_time')) ? old('test_time') : '' }}"
                @else
                value = "{{ $obj->test_time }}"
                @endif
              >
          </div>
        </div>
      </div>


    

      <div class="row">
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Price</label>
            <input type="text" class="form-control" name="price" id="formGroupExampleInput" placeholder="Enter the price in rupees" 
                @if($stub=='Create')
                value="{{ (old('price')) ? old('price') : '0' }}"
                @else
                value = "{{ $obj->price }}"
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
            <label for="formGroupExampleInput ">Level</label>
            <select class="form-control" name="level">
              <option value="0" @if(isset($obj)) @if($obj->level==0) selected @endif @endif >- None -</option>
              @for($i=5;$i>0;$i--)
              <option value="{{$i}}" @if(isset($obj)) @if($obj->level==$i) selected @endif @endif >Level {{$i}}</option>
              @endfor
            </select>
          </div>
        </div>
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label for="formGroupExampleInput ">Status</label>
            <select class="form-control" name="status">
              <option value="0" @if(isset($obj)) @if($obj->status==0) selected @endif @endif >Inactive</option>
              <option value="1" @if(isset($obj)) @if($obj->status==1) selected @endif @endif >Active </option>
              <option value="2" @if(isset($obj)) @if($obj->status==2) selected @endif @endif >Open (No Login Required)</option>
              <option value="3" @if(isset($obj)) @if($obj->status==3) selected @endif @endif >Private (API driven)</option>
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

      </div>

      
      @if($stub=='Update')
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{ $obj->id }}">
      @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <button type="submit" class="btn btn-primary btn-lg">Save</button>
    </form>
    </div>
  </div>
@endsection