@extends('layouts.bg')
@section('title', 'S'.$obj->sno.' | First Academy')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')
@section('content')

<div class="bg-white   mb-4">
<div class="container">
<nav >
  <ol class="breadcrumb bg-white p-0 pt-3 pb-3">
   <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin/test')}}">Test</a></li>
    <li class="breadcrumb-item"><a href="{{ route('test.show',$app->test->id)}}">{{$app->test->name}}</a></li>
    <li class="breadcrumb-item"><a href="{{ route($app->module.'.index',[$app->test->id]) }}">{{ ucfirst($app->module) }}</a></li>
    <li class="breadcrumb-item">S{{ $obj->sno }}</li>
  </ol>
</nav>
</div>
</div>





<div class="container">
  @include('flash::message')
  <div class="row">

    <div class="col-12 col-md-2">
       <div class="block mb-4">
      <div class="  p-3" style="background: #d4ffd8">
        <dl class="row mb-0 no-gutters">
          <dt class="col-sm-5"><b> Sno</b></dt>
          <dd class="col-sm "> : @if($obj->sno)
                {{ $obj->sno }}
                @else
                -
                @endif</dd>
        </dl>
        <dl class="row no-gutters mb-0">
          <dt class="col-sm-5"><b> Qno</b></dt>
          <dd class="col-sm"> : @if($obj->qno)
              {{ $obj->qno }} 
              @else
              -
              @endif</dd>
        </dl>
        <dl class="row no-gutters mb-0">
          <dt class="col-sm-5"><b> Layout</b></dt>
          <dd class="col-sm" style="word-wrap: break-word;">
                : 
                @if($obj->layout) 
                  @if($obj->layout=='ielts_two_blank')
                    Two blanks - Format 1
                  @elseif($obj->layout=='two_blank')
                    Two blanks - Format 2
                  @elseif($obj->layout=='cloze_test')
                   Dropdown - Format 1
                  @elseif($obj->layout=='dropdown')
                   Dropdown - Format 2
                  @elseif($obj->layout=='duolingo_missing_letter')
                  Missing Letters
                  @elseif($obj->layout=='ielts_label')
                  Label Columns
                  @elseif($obj->layout=='ielts_number')
                  Numbered Blank
                  @else
                  {{ $obj->layout }} 
                  @endif
                @else 
                  Default 
                @endif</dd>
        </dl>
      </div>

    </div>
  </div>
    <div class="col-12 col-md-8">
    
    <div class="mb-3">
          @can('update',$obj)
            <span class="btn-group float-right" role="group" aria-label="Basic example">
              <a href="{{ route($app->module.'.edit',[$app->test->id,$obj->id]) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i> edit</a>
              <a href="{{ route($app->module.'.d',[$app->test->id,$obj->id]) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="make a copy of the question"><i class="fa fa-retweet"></i> duplicate</a>
              <a href="#" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal" data-tooltip="tooltip" data-placement="top" title="Delete" ><i class="fa fa-trash"></i> delete</a>
            </span>
            @endcan
          <p class="h2 mb-2 d-inline" >
            <i class="fa fa-gg "></i> 
            Preview
          </p>
    </div>

    <div class="p-4 rounded bg-white border-top">
     @if($f->qno==-1)
      @include('appl.test.attempt.layouts.fillup_example') 
    @else
      @if($f->layout=='gre_sentence')
        @include('appl.test.attempt.layouts.gre_sentence') 
      @elseif($f->layout=='dropdown')
        @include('appl.test.attempt.layouts.dropdown') 
      @elseif($f->layout=='paragraph')
        @include('appl.test.attempt.layouts.ielts_paragraph') 
      @elseif($f->layout=='duolingo_missing_letter')
        @include('appl.test.attempt.layouts.duolingo_missing_letter') 
      @elseif($f->layout=='cloze_test')
        @include('appl.test.attempt.layouts.cloze_test') 
      @elseif($f->layout=='ielts_two_blank')
      <div class=" question">
        <div class="card-text">
        @include('appl.test.attempt.layouts.ielts_two_blank') 
      </div>
      </div>
      @elseif($f->layout=='two_blank')
        @include('appl.test.attempt.layouts.two_blank') 
      @elseif($f->layout=='ielts_label')
        @include('appl.test.attempt.layouts.ielts_label') 
      @elseif($f->layout=='ielts_number')
        @include('appl.test.attempt.layouts.ielts_title') 
      @elseif($f->layout=='listen_audio_options')
        @include('appl.test.attempt.layouts.listen_audio_options') 
      @elseif($f->layout=='listen_audio_question')
        @include('appl.test.attempt.layouts.listen_audio_question')   
      @elseif($f->layout=='speak')
        @include('appl.test.attempt.layouts.speak')  
      @elseif($f->layout=='write')
        @include('appl.test.attempt.layouts.write')  
      @elseif($f->layout=='select_words')
        @include('appl.test.attempt.layouts.select_words')  
      @else
        @include('appl.test.attempt.layouts.gre_blank') 
      @endif   
    @endif
  </div>

  <div class="mt-4"><p class="h2 mb-2 d-inline" >
            <i class="fa fa-question-circle-o "></i> 
            Question
          </p></div>
  <div class="p-4 rounded bg-white border mt-4 mb-4">
    <div class="row">
      <div class="col-12 col-md-3">
        <p class="text-secondary text-wrap"><i class="fa fa-angle-right"></i> Label</p>
        <h5 class="text-wrap text-break">{{$obj->label}}</h5>
      </div>
       <div class="col-12 col-md-3">
        <p class="text-secondary"><i class="fa fa-angle-right"></i> Prefix</p>
        <h5>{{$obj->prefix}}</h5>
      </div>
       <div class="col-12 col-md-3">
        <p class="text-secondary"><i class="fa fa-angle-right"></i> Answer</p>
        <h5>{{$obj->answer}}</h5>
      </div>
       <div class="col-12 col-md-3">
        <p class="text-secondary"><i class="fa fa-angle-right"></i> Suffix</p>
        <h5>{{$obj->suffix}}</h5>
      </div>
    </div>

      <hr>

          <div class="row mb-2">
            <div class="col-md-4"><b>Mark </b></div>
            <div class="col-md-8">{{ ($obj->mark) ? $obj->mark : '' }}</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Created </b></div>
            <div class="col-md-8">{{ ($obj->created_at) ? $obj->created_at->diffForHumans() : '' }}</div>
          </div>
  </div>



<style>

.circle-audio-player {
  display: block;
  margin: 0 auto;
  cursor: pointer;
}
.cplayer {
  padding: 4px;
}


</style>

 
  <div class="p-4 rounded bg-white border mt-4 mb-4">
  <h2>Audio</h2>
  <hr>
    @if(\Storage::disk('public')->exists('extracts/'.$obj->id.'_q.mp3') )
    Question<br>
    <div id="playerContainer_q" class="cplayer" data-src="{{ asset('/storage/extracts/'.$obj->id.'_q.mp3') }}"></div>
    @endif

    <div class="row">
      
    @foreach(["a","b","c","d","e","f","g","h","i","j",'k',"l"] as $item)
    
    
    @if(\Storage::disk('public')->exists('extracts/'.$obj->id.'_'.$item.'.mp3') )
    <div class="col-md-3 ">
      <div class="mb-3 bg-light  p-3 rounded">
    <div class="mb-2">{{ 'Option - '.strtoupper($item)}}</div>
      <div id="playerContainer_{{$item}}" class="cplayer" data-src="{{ asset('/storage/extracts/'.$obj->id.'_'.$item.'.mp3') }}"></div>
      </div>
    </div>
    @endif
  
    @endforeach
  </div>
  </div>
  

    </div>

    <div class="col-12 col-md-2">
      @include('appl.test.snippets.menu')
    </div>
     
</div>
  </div> 


  <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        This following action is permanent and it cannot be reverted.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
        <form method="post" action="{{route($app->module.'.destroy',[$app->test->id,$obj->id])}}">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        	<button type="submit" class="btn btn-danger">Delete Permanently</button>
        </form>
      </div>
    </div>
  </div>
</div>


@endsection