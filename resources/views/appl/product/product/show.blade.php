@extends('layouts.app')
@section('title',$obj->name.' - First Academy' )
@section('description', strip_tags($obj->description))
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET, '.$obj->name)
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

  <div class="row">

    <div class="col-md-12">
      <div class="card bg-light mb-3">
        <div class="card-body text-secondary">
          <p class="h2 mb-0"><i class="fa fa-th "></i> {{ $obj->name }} 

          @can('update',$obj)
            <span class="btn-group float-right" role="group" aria-label="Basic example">
              <a href="{{ route($app->module.'.edit',$obj->id) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
              <a href="{{ route('product.view',$obj->slug)}}" class="btn btn-outline-secondary"  target="_blank"><i class="fa fa-eye"></i></a>
              <a href="#" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal" data-tooltip="tooltip" data-placement="top" title="Delete" ><i class="fa fa-trash"></i></a>
            </span>
            @endcan
          </p>
        </div>
      </div>

     
      <div class="card mb-4">
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-4"><b>Name</b></div>
            <div class="col-md-8">{{ $obj->name }}</div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Slug</b></div>
            <div class="col-md-8">{{ $obj->slug }}</div>
          </div>
           <div class="row mb-2">
            <div class="col-md-4"><b>Price</b></div>
            <div class="col-md-8"><i class="fa fa-rupee"></i> {{ $obj->price }}</div>
          </div>
           <div class="row mb-2">
            <div class="col-md-4"><b>Validity</b></div>
            <div class="col-md-8">{{ $obj->validity }} months</div>
          </div>
           <div class="row mb-2">
            <div class="col-md-4"><b>Tags</b></div>
            <div class="col-md-8">@if(isset($settings->tags)) {{ $settings->tags }} @else - @endif</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Image</b></div>
            <div class="col-md-8">
              @if(\Storage::disk('public')->exists($obj->image) && $obj->image )
              <div class="bg-light border  p-3 mb-3">
                <img src="{{ asset('storage/'.$obj->image)}}"  class="w-25 "/>
              </div>
              <form method="post" action="{{route($app->module.'.update',[$obj->id])}}" >
                 <input type="hidden" name="_method" value="PUT">
                 <input type="hidden" name="deletefile" value="1">
                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-sm btn-outline-danger">Delete File</button>
              </form>
              @else
               <span class="text-muted"><i class="fa fa-exclamation-triangle"></i> file path not found </span>
              @endif
            </div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Description</b></div>
            <div class="col-md-8">{!! $obj->description !!}</div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Details</b></div>
            <div class="col-md-8">{!! $obj->details !!}</div>
          </div>
          
          @if(count($obj->mocks))
          <div class="row mb-2">
            <div class="col-md-4"><b>Mocks</b></div>
            <div class="col-md-8">
              @foreach($obj->mocks as $mock)
                <a href="{{ route('mock.show',$mock->id)}}">
                  {{$mock->name }} ({{ $mock->slug }})
                </a><br>
              @endforeach
            </div>
          </div>
          @endif

          @if(count($obj->tests))
          <div class="row mb-2">
            <div class="col-md-4"><b>Tests</b></div>
            <div class="col-md-8">
              @foreach($obj->tests as $test)
                <a href="{{ route('test.show',$test->id)}}">
                  {{$test->name }} ({{ $test->slug }})
                </a><br>
              @endforeach
            </div>
          </div>
          @endif
          <div class="row mb-2">
            <div class="col-md-4"><b>Status</b></div>
            <div class="col-md-8">@if($obj->status==0)
                    <span class="badge badge-warning">Inactive</span>
                  @elseif($obj->status==1)
                    <span class="badge badge-success">Active</span>
                  @endif</div>
          </div>
         
          <div class="row mb-2">
            <div class="col-md-4"><b>Created at</b></div>
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


@endsection