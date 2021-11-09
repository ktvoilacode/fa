@extends('layouts.app')
@section('title', $obj->value. ' | Category Form | First Academy')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb border bg-light">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route($app->module.'.index') }}">{{ ucfirst($app->module) }}</a></li>
    <li class="breadcrumb-item">{{ $obj->name }}</li>
  </ol>
</nav>

@include('flash::message')


 <div class="card bg-light mb-3">
        <div class="card-body text-secondary">
          <p class="h2 mb-0"><i class="fa fa-th "></i> {{ $obj->name }} 

          @can('update',$obj)
            <span class="btn-group float-right" role="group" aria-label="Basic example">
              <a href="{{ route($app->module.'.edit',$obj->id) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
              <a href="#" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal" data-tooltip="tooltip" data-placement="top" title="Delete" ><i class="fa fa-trash"></i></a>
            </span>
            @endcan
          </p>
        </div>
      </div>

  <div class="row">

    <div class="col-md-6">
     

     
      <div class="card mb-4">
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-4"><b>Name</b></div>
            <div class="col-md-8">
              @if($user)
              <a href="{{ route('user.show',$user->id)}}">{{ $obj->name }}</a>
              @else
              <b class="text-primary">{{ $obj->name }}</b>
              @endif
            </div>
          </div>
         
         <div class="row mb-2">
            <div class="col-md-4"><b>Email</b></div>

            <div class="col-md-8">@if($user)
              <a href="{{ route('user.show',$user->id)}}">{{ $obj->email }}</a>
              @else
              <b class="text-primary">{{ $obj->email }}</b>
              @endif</div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Phone</b></div>
            <div class="col-md-8">{{ $obj->phone }}</div>
          </div>

          

          

          <div class="row mb-2">
            <div class="col-md-4"><b>Created</b></div>
            <div class="col-md-8">{{ ($obj->created_at) ? $obj->created_at->diffForHumans() : '' }}</div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class='card-body'>
        <div class="row mb-2">
            <div class="col-md-4"><b>College</b></div>
            <div class="col-md-8">@if($obj->college){{ $obj->college }} @else - @endif</div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Year of Passing</b></div>
            <div class="col-md-8">@if($obj->year) {{ $obj->year }} @else - @endif</div>
          </div>
      </div>
    </div>




    </div>

    <div class="col-md-6">
      <div class="card mb-3">
        <div class='card-body'>
        <div class="row mb-2">
            <div class="col-md-4"><b>Subject</b></div>
            <div class="col-md-8">{{ $obj->subject }}</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Message</b></div>
            <div class="col-md-8">{!! $obj->description !!}</div>
          </div>
      </div>
    </div>

    <div class="card">
        <div class='card-body'>
        <div class="row mb-2">
            <div class="col-md-4"><b>Updated By </b></div>
            <div class="col-md-8">@if($obj->user) {{ $obj->user->name }} @else - @endif</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Comment</b></div>
            <div class="col-md-8">{!! $obj->comment !!}</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Status</b></div>
            <div class="col-md-8">
               @if($obj->status==0)
                  <span class="badge badge-warning">Open</span>
                  @elseif($obj->status==1)
                  <span class="badge badge-primary">Closed</span>
                  @elseif($obj->status==2)
                  <span class="badge badge-primary">Closed + Notified User</span>
                  @endif
            </div>
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


@endsection