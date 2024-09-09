@extends('layouts.app')
@section('title', 'Test Response | First Academy')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb border bg-light">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route($app->module.'.index') }}?type=writing">{{ ucfirst($app->module) }}</a></li>
    <li class="breadcrumb-item"> User Response</li>
  </ol>
</nav>

@include('flash::message')
<div class="row">

  <div class="col-md-12">
    <div class="card bg-light mb-3">
      <div class="card-body text-secondary">
        <div class="row">
          <div class="col-12 col-md-6">
            <h2><i class="fa fa-file-o "></i> {{ $obj->test->name }} -
              @if($obj->user)
              {{ $obj->user->name }}
              @else
              @if($obj->session)
              <br>{{$obj->session->name}} - {{$obj->session->phone}}
              @else
              {{ $obj->session_id}}
              @endif
              @endif
            </h2>
          </div>
          <div class="col-12 col-md-6">
            <div class="btn-group">

            </div>
            @can('update',$obj)
            <span class="btn-group float-right" role="group" aria-label="Basic example">

              <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Notify <i class="fa fa-whatsapp"></i> <i class="fa fa-envelope-o"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <a href="{{ route('review.notify',$obj->id)}}" class="dropdown-item">Now</a>
                  <a href="{{ route('review.notify',$obj->id)}}?time=14" class="dropdown-item">Schedule - 14:30 </a>
                  <a href="{{ route('review.notify',$obj->id)}}?time=20" class="dropdown-item">Schedule - 20:30</a>
                </div>
              </div>


              <a href="{{ route($app->module.'.edit',$obj->id) }}" class="btn btn-primary " data-tooltip="tooltip" data-placement="top" title="Edit"> Edit</a>

              @if($obj->user)
              <a href="{{ route('test.review',$obj->test->slug) }}?user_id={{$obj->user->id}}" class="btn btn-outline-secondary " data-tooltip="tooltip" data-placement="top" title="review"> Review</a>
              @else
              <a href="{{ route('test.review',$obj->test->slug) }}?session_id={{$obj->session_id}}" class="btn btn-outline-secondary " data-tooltip="tooltip" data-placement="top" title="review"> Review</a>

              @endif
              @if(\auth::user()->admin!=4)
              <a href="{{ route('file.assign',$obj->id) }}" class="btn btn-outline-secondary " data-tooltip="tooltip" data-placement="top" title="review"> Assign</a>

              <a href="#" class="btn btn-outline-secondary " data-toggle="modal" data-target="#exampleModal" data-tooltip="tooltip" data-placement="top" title="Delete"> Delete</a>
              @endif
            </span>
            @endcan

          </div>
        </div>
      </div>
    </div>


    @if($writing)
    @if($writing->user_id)
    <div class="alert alert-primary alert-important" role="alert">
      This task is assigned to <b>{{ $writing->user->name }}</b>
    </div>
    @endif
    @if($writing->notify)
    <div class="alert alert-warning alert-important" role="alert">
      Notification scheduled for <b>{{ $writing->notify }}:30 </b>
    </div>
    @endif
    @endif



    <div class="card mb-4">
      <div class="card-body">

        @if(strlen(strip_tags($obj->test->description))>0)
        <div class="row mb-2">
          <div class="col-md-4"><b>Question</b></div>
          <div class="col-md-8">
            <div class="p-3 border mb-3 bg-light">
              {!! $obj->test->description !!}</div>
          </div>

        </div>
        @endif

        <div class="row mb-2">
          <div class="col-md-4"><b>Response</b></div>
          <div class="col-md-8">
            <div class="p-3 border mb-3 bg-light" style="white-space: pre-wrap;">{!! $obj->response !!}</div>
            <a href="{{route($app->module.'.download',[$obj->id])}}">
              <button type="button" class="btn btn btn-primary float-left mr-2 mb-2">Download Response PDF</button>
            </a>
            <a href="{{route($app->module.'.download',[$obj->id])}}?word=1">
              <button type="button" class="btn btn btn-success float-left mr-2 mb-2">Download Response WORD</button>
            </a>
          </div>

        </div>

        <div class="row mb-2">
          <div class="col-md-4"><b>Expert Feedback</b></div>
          <div class="col-md-8">
            @if($obj->answer)
            <div class="p-3 bg-light border">{!! $obj->answer !!}</div>
            @else
            - NA -
            @endif
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-md-4"><b>Score</b></div>
          <div class="col-md-8">
            @if($obj->score)
            <div class="p-3 bg-light border">{!! $obj->score !!}</div>
            @else
            - NA -
            @endif
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-md-4"><b>PDF Doc</b></div>
          <div class="col-md-8">
            @if(Storage::disk('public')->exists('feedback/feedback_'.$obj->id.'.pdf'))
            <div class="bg-light border  p-3 mb-3">
              feedback_{{ $obj->id.'.pdf' }}
            </div>

            <a href="{{route($app->module.'.download',[$obj->id])}}?pdf=1">
              <button type="button" class="btn  btn-success float-left mr-2">Download Feedback</button>
            </a>


            <a href="#" class="btn btn-outline-secondary " data-toggle="modal" data-target="#exampleModal2" data-tooltip="tooltip" data-placement="top" title="Delete"> Delete Feedback</a>

            @else
            <span class="text-muted"><i class="fa fa-exclamation-triangle"></i> file path not found </span>
            @endif
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-md-4"><b>Uploaded at</b></div>
          <div class="col-md-8">{{ ($obj->created_at) ? $obj->created_at->diffForHumans() : '' }}</div>
        </div>
      </div>
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

        <form method="post" action="{{route($app->module.'.destroy',$obj->id)}}">
          <input type="hidden" name="_method" value="DELETE">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <button type="submit" class="btn btn-danger">Delete Permanently</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm Feedback Deletion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        This following action is permanent and it cannot be reverted.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

        <form method="post" action="{{route($app->module.'.update',[$obj->id])}}" class="form-inline" role="form">
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="deletefile" value="1">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <button type="submit" class="btn btn-danger ml-2">Delete Feedback</button>
        </form>

      </div>
    </div>
  </div>
</div>



@endsection