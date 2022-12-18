@extends('layouts.bg')
@section('title', $obj->code.' | '.client('name'))

@section('content')


<div class="bgblue  bdbblue mb-4" >
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb pl-0 mb-1 bgblue" >
        <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
        <li class="breadcrumb-item"><a href="{{ route($app->module.'.index') }}">{{ ucfirst($app->module) }}</a></li>
        <li class="breadcrumb-item">{{ $obj->client_slug }}</li>
      </ol>
    </nav>
    <div class="row mb-3">
      <div class="col-12 col-md-8">
        <h3 class="mb-4"><i class="fa fa-bars"></i>  {{ $obj->client_slug }} </h3>
      </div>
      <div class="col-12 col-md-4">
        @can('update',$obj)
            <span class="btn-group float-right" role="group" aria-label="Basic example">
              <a href="{{ route($app->module.'.edit',$obj->id) }}?client_slug={{$obj->client_slug}}" class="btn btn-success" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>

              <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" data-tooltip="tooltip" data-placement="top" title="Delete" ><i class="fa fa-trash"></i></a>
            </span>
            @endcan
      </div>
    </div>
  </div>
</div>


<div class="container">
@include('flash::message')

  <div class="row">

    <div class="col-md-12">
   

     
      <div class="card mb-4">
        <div class="card-body">
          
          <div class="row mb-2">
            <div class="col-md-4"><b>Client</b></div>
            <div class="col-md-8">{{ $obj->client_slug }}</div>
          </div>
          
          <div class="row mb-2">
            <div class="col-md-4"><b>Credits</b></div>
            <div class="col-md-8">{{ $obj->credit }}</div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Payment Mode</b></div>
            <div class="col-md-8">{{ $obj->payment_mode }}</div>
          </div>

          
         
         <div class="row mb-2">
            <div class="col-md-4"><b>Status</b></div>
            <div class="col-md-8">@if($obj->status==0)
                    <span class="badge badge-danger">Used</span>
                  @elseif($obj->status==1)
                    <span class="badge badge-success">Active</span>
                  @endif</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Created </b></div>
            <div class="col-md-8">{{ ($obj->created_at) ? $obj->created_at->diffForHumans() : '' }}</div>
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