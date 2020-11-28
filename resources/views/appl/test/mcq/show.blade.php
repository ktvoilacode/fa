@extends('layouts.bg')
@section('title', 'Q'.$obj->qno.' | First Academy')
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
                : @if(!$obj->layout || $obj->layout=='no_instruction' || $obj->layout=='gre1')
                Default 
                @elseif($obj->layout=='gre2')
                2 columns 6 options 
                @elseif($obj->layout=='gre3')
                3 columns 9 options
                @elseif($obj->layout=='gre_maq')
                Multi Answer
                @elseif($obj->layout=='gre_numeric')
                Numeric Entry
                @elseif($obj->layout=='gre_fraction')
                Fraction
                @elseif($obj->layout=='gre_sentence')
                Sentence Selection
                @elseif($obj->layout=='pte_maq')
                PTE Multi Answer
                @elseif($obj->layout=='pte_mcq')
                PTE MCQ
                @else {{ $obj->layout }} @endif</dd>
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


     <div class="p-4 rounded bg-white border-top mb-4">
      @include('appl.test.attempt.blocks.mcq_layouts') 
  </div>

      <div class="p-4 mb-4 bg-white">
        <div class="">
          
          @if($obj->explanation)
          <div class="row mb-2">
            <div class="col-md-4"><b>Explanation</b></div>
            <div class="col-md-8">{!! $obj->explanation !!}</div>
          </div>
          @endif

        
          
          
          <div class="row mb-2">
            <div class="col-md-4"><b>Answer</b></div>

            <div class="col-md-8">
            @if($obj->layout=='gre_sentence' || $obj->layout=='gre_numeric')
              {!! $obj->a !!}
            @elseif($obj->layout=='gre_fraction')
              {!! $obj->a !!} / {!! $obj->b !!}
            @else
              {{ $obj->answer }}
            @endif
            </div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Mark </b></div>
            <div class="col-md-8">{{ ($obj->mark) ? $obj->mark : '' }}</div>
          </div>
         
          
          <div class="row mb-2">
            <div class="col-md-4"><b>Created </b></div>
            <div class="col-md-8">{{ ($obj->created_at) ? $obj->created_at->diffForHumans() : '' }}</div>
          </div>
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