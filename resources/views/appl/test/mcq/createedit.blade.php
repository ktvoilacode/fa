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

      @if($stub=='Create')
        @if(request()->get('editor'))
        <a href="{{ route('mcq.create',[$app->test->id,$obj->id])}}" class="float-right btn  btn-outline-danger">Disable Editor</a>
        @else
        <a href="{{ route('mcq.create',[$app->test->id,$obj->id])}}editor=1" class="float-right btn  btn-outline-primary">Enable Editor</a>
        @endif
      @else
        @if(request()->get('editor'))
        <a href="{{ route('mcq.edit',[$app->test->id,$obj->id])}}" class="float-right btn  btn-outline-danger">Disable Editor</a>
        @else
        <a href="{{ route('mcq.edit',[$app->test->id,$obj->id])}}?editor=1" class="float-right btn  btn-outline-primary">Enable Editor</a>
        @endif

      @endif

       </h1>
      
      @if($stub=='Create')
      <form method="post" action="{{route($app->module.'.store',$app->test->id)}}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{route($app->module.'.update',[$app->test->id,$obj->id])}}" enctype="multipart/form-data">
      @endif  
      
      
      <div class="row">
        <div class="col-12 col-md">
          <div class="form-group">
        <label for="formGroupExampleInput ">Qno</label>
        <input type="text" class="form-control" name="qno" id="formGroupExampleInput" placeholder="Enter the Question number" 
            @if($stub=='Create')
            value="{{ (old('qno')) ? old('qno') : $app->qno }}"
            @else
            value = "{{ $obj->qno }}"
            @endif
          >
      </div>

        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
        <label for="formGroupExampleInput ">Test</label>
        <input type="text" class="form-control" name="test_" id="formGroupExampleInput" value="{{$app->test->name}}" disabled>
      </div>
        </div>

        <div class="col-12 col-md">
          <div class="form-group">
        <label for="formGroupExampleInput ">Passage</label>
          <select class="form-control" name="extract_id">
          <option value="" @if(isset($obj)) @if(!$obj->extract_id) selected @endif @endif >- None -</option>
          @foreach($extracts as $extract)
          <option value="{{$extract->id}}" @if(isset($obj)) @if($obj->extract_id == $extract->id) selected  @endif  @endif >{{ $extract->name }}</option>
          @endforeach
        </select>
      </div>

        </div>
        
          @if(!in_array(strtolower($app->test->testtype->name),['grammar']))
          <div class="col-12 col-md">
      <div class="form-group">
        <label for="formGroupExampleInput ">Section</label>
        <select class="form-control" name="section_id">
          <option value="" @if(isset($obj)) @if(!$obj->section_id) selected @endif @endif >- None -</option>
          @foreach($sections as $section)
          <option value="{{$section->id}}" @if(isset($obj)) @if($obj->section_id == $section->id) selected  @endif @endif  >{{ $section->name }}</option>
          @endforeach
        </select>
      </div>
      </div>
      @endif

      <div class="col-12 col-md">
          <div class="form-group">
        <label for="formGroupExampleInput ">Mark</label>
        <input type="text" class="form-control" name="mark" id="formGroupExampleInput" placeholder="Enter the mark" 
            @if($stub=='Create')
            value="{{ (old('mark')) ? old('mark') : 1}}"
            @else
            value = "{{ $obj->mark }}"
            @endif
          >
      </div>

        </div>
        
      </div>

      <div class="row">
        <div class="col-12 ">
      <div class="form-group">
        <label for="formGroupExampleInput ">Question</label>
<textarea class="form-control @if(request()->get('editor')) summernote @endif" name="question"  rows="3">@if($stub=='Create'){{ (old('question')) ? old('question') : '' }} @else{{ $obj->question }}
            @endif
        </textarea>
      </div>
    </div>
    
    </div>

      <div class="row">
      


        @if(!$obj->layout || $obj->layout=='no_instruction' || $obj->layout=='gre1' || $obj->layout=='gre2' || $obj->layout=='gre3' || $obj->layout=='gre_maq')
        @foreach(['a','b','c','d','e','f'] as $opt)
        <div class="col-12 col-md-4">
      <div class="form-group">
        <label for="formGroupExampleInput ">Option {{ strtoupper($opt)}}</label><textarea class="form-control @if(request()->get('editor')) summernote @endif" name="{{$opt}}"  rows="2">@if($stub=='Create'){{ (old($opt)) ? old($opt) : '' }} @else{{$obj->$opt }} @endif </textarea>
      </div>
    </div>
      @endforeach
      @endif

      @if($obj->layout=='gre3' || $obj->layout=='gre_maq')
      @foreach(['g','h','i'] as $opt)
        <div class="col-12 col-md-4">
      <div class="form-group">
        <label for="formGroupExampleInput ">Option {{ strtoupper($opt)}}</label><textarea class="form-control @if(request()->get('editor')) summernote @endif" name="{{$opt}}"  rows="2">@if($stub=='Create'){{ (old($opt)) ? old($opt) : '' }} @else{{$obj->$opt }} @endif </textarea>
      </div>
    </div>
      @endforeach
      @endif
        
      </div>

      <div class="row">
        <div class="col-12 col-md-4">
          <div class="form-group">
        <label for="formGroupExampleInput ">Explanation / Solution</label>
<textarea class="form-control @if(request()->get('editor')) summernote @endif" name="explanation"  rows="3">@if($stub=='Create'){{ (old('explanation')) ? old('explanation') : '' }} @else {{ $obj->explanation }} @endif </textarea>
      </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
        <label for="formGroupExampleInput">Answers</label>
         <div class=" card bg-light p-3">
          <div class="row">

          @if($obj->layout=='gre_numeric')
        @foreach(['a'] as $opt)
        <div class="col-12 ">
      <div class="form-group">
        <label for="formGroupExampleInput ">Numeric Answer</label><textarea class="form-control w-100 @if(request()->get('editor')) summernote @endif" name="{{$opt}}"  rows="1">@if($stub=='Create'){{ (old($opt)) ? old($opt) : '' }} @else{{$obj->$opt }} @endif </textarea>
      </div>
    </div>
      @endforeach
      @endif

      @if($obj->layout=='gre_sentence')
        @foreach(['a'] as $opt)
        <div class="col-12 ">
      <div class="form-group">
        <label for="formGroupExampleInput ">Sentence</label><textarea class="form-control w-100 @if(request()->get('editor')) summernote @endif" name="{{$opt}}"  rows="1">@if($stub=='Create'){{ (old($opt)) ? old($opt) : '' }} @else{{$obj->$opt }} @endif </textarea>
      </div>
    </div>
      @endforeach
      @endif

      @if($obj->layout=='gre_fraction')
        @foreach(['a'] as $opt)
        <div class="col-12 col-md-6">
      <div class="form-group">
        <label for="formGroupExampleInput ">Numerator</label><textarea class="form-control @if(request()->get('editor')) summernote @endif" name="{{$opt}}"  rows="1">@if($stub=='Create'){{ (old($opt)) ? old($opt) : '' }} @else{{$obj->$opt }} @endif </textarea>
      </div>
    </div>
      @endforeach
      @foreach(['b'] as $opt)
        <div class="col-12 col-md-6">
      <div class="form-group">
        <label for="formGroupExampleInput ">Denominator</label><textarea class="form-control @if(request()->get('editor')) summernote @endif" name="{{$opt}}"  rows="1">@if($stub=='Create'){{ (old($opt)) ? old($opt) : '' }} @else{{$obj->$opt }} @endif </textarea>
      </div>
    </div>
      @endforeach
      @endif
      
      @if(!$obj->layout || $obj->layout=='no_instruction' || $obj->layout=='gre1' || $obj->layout=='gre2')
        @foreach(['a','b','c','d','e','f'] as $ans)
          <div class="col-12 col-md-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="answers[]" value="{{strtoupper($ans)}}" id="defaultCheck1" @if(strpos($obj->answer,strtoupper($ans))!==FALSE) checked @endif>
            <label class="form-check-label" for="defaultCheck1">
              {{ strtoupper($ans) }}
            </label>
          </div>
          </div>
          @endforeach

      @endif

      @if( $obj->layout=='gre3' || $obj->layout=='gre_maq')
          @foreach(['a','b','c','d','e','f','g','h','i'] as $ans)
          <div class="col-12 col-md-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="answers[]" value="{{strtoupper($ans)}}" id="defaultCheck1" @if(strpos($obj->answer,strtoupper($ans))!==FALSE) checked @endif>
            <label class="form-check-label" for="defaultCheck1">
              {{ strtoupper($ans) }}
            </label>
          </div>
          </div>
          @endforeach
      @endif

      
         </div>
         </div>
      </div>
        </div>
        <div class="col-12 col-md-4">
           <div class="form-group">
        <label for="formGroupExampleInput ">Layout</label>
        <select class="form-control" name="layout">
          <option value="" @if(isset($obj)) @if(!$obj->layout || $obj->layout=='no_instruction' || $obj->layout=='gre1' || $obj->layout=='pte_maq') selected @endif @endif >Default</option>
          <option value="gre2" @if(isset($obj)) @if($obj->layout=='gre2') selected @endif @endif >2 Column 6 Options</option>
          <option value="gre3" @if(isset($obj)) @if($obj->layout=='gre3') selected @endif @endif >3 Column 9 Options</option>
          <option value="gre_maq" @if(isset($obj)) @if($obj->layout=='gre_maq' || $obj->layout=='pte_mcq') selected @endif @endif >Multi Answer</option>
          <option value="gre_numeric" @if(isset($obj)) @if($obj->layout=='gre_numeric') selected @endif @endif >Numeric Entry</option>
          <option value="gre_fraction" @if(isset($obj)) @if($obj->layout=='gre_fraction') selected @endif @endif >Fraction</option>
          <option value="gre_sentence" @if(isset($obj)) @if($obj->layout=='gre_sentence') selected @endif @endif >Sentence Selection</option>
        </select>
      </div>
      <small class="text-secondary"> Layout is the template design on how the question should look in the user view.  <a href="{{ route('mcq')}}"><i class="fa fa-link"></i> help images</a></small>
        </div>
      </div>


       <div class="form-group">
        <label for="formGroupExampleInput">Tags</label>
        
        <div class="row">
          
          <div class="col-12 col-md-6">
            <small class="text-secondary"> Experimental feature for future use. We might use this to tag the questions to different keywords. (you can ignore this for timebeing)</small>
            @foreach($tags->groupBy('name') as $name=>$t)
          <div class="border rounded p-2 mb-1 bg-light mb-4">
          <div class="row">
          <div class="col-12 col-md-12">
            <div class="pr-2 pl-2 pt-1 pb-1"><b class="text-primary ">{{$name}}</b></div>
            </div>
          @foreach($t as $tag)
          <div class="col-12 col-md-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tags[]" value="{{$tag->id}}" id="defaultCheck1" @if($obj->tags->contains($tag->id))) checked @endif>
            <label class="form-check-label" for="defaultCheck1">
               {{ $tag->value }}
            </label>
          </div>
          </div>
          @endforeach
        </div>
          </div>
          @endforeach
        </div>
          
          <div class="col-12 col-md-6">
          <div class="form-group border rounded p-3">
            <label for="formGroupExampleInput " class="">Layout Preview:</label>
            @if(request()->get('layout'))
              <img src="{{ asset('images/tests/mcq/'.request()->get('layout').'.png')}}" class="w-100 mb-3">
            @elseif($obj->layout)
              <img src="{{ asset('images/tests/mcq/'.$obj->layout.'.png')}}" class="w-100 mb-3">
            @else
            <b>Default</b>
            <img src="{{ asset('images/tests/mcq.png')}}" class="w-100 mb-3">
            @endif
            <small class="py-4">Note: You can refer this page for other layouts. <a href="{{ route('mcq')}}">check layouts</a></small>
          </div>

        </div>
        </div>
      </div>


      @if($stub=='Update')
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{ $obj->id }}">
        <input type="hidden" name="sno" value="{{ $obj->sno }}">
      @else
      <input type="hidden" name="sno" value="{{ $app->sno }}">  
      @endif
      
      <input type="hidden" name="test_id" value="{{ $app->test->id }}">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <button type="submit" class="btn btn-primary btn-lg">Save</button>
    </form>
    </div>
  </div>
@endsection