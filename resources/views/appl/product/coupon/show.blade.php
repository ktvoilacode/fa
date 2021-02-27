@extends('layouts.app')
@section('title', $obj->code.' | First Academy')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')

@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb border bg-light">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route($app->module.'.index') }}">{{ ucfirst($app->module) }}</a></li>
    <li class="breadcrumb-item">{{ $obj->code }}</li>
  </ol>
</nav>

@include('flash::message')

  <div class="row">

    <div class="col-md-12">
      <div class="card bg-light mb-3">
        <div class="card-body text-secondary">
          <p class="h2 mb-0"><i class="fa fa-th "></i> {{ $obj->code }} 

          @can('update',$obj)
            <span class="btn-group float-right" role="group" aria-label="Basic example">
              <a href="{{ route($app->module.'.edit',$obj->id) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>

              <a href="#" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal" data-tooltip="tooltip" data-placement="top" title="Delete" ><i class="fa fa-trash"></i></a>
            </span>
            @endcan
          </p>
        </div>
      </div>

     
      <div class="card mb-4">
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-4"><b>Code</b></div>
            <div class="col-md-8">{{ $obj->code }}</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Expiry</b></div>
            <div class="col-md-8">{{($obj->expiry) ? $obj->expiry : ''}}</div>
          </div>

           @if(count($obj->products))
          <div class="row mb-2">
            <div class="col-md-4"><b>Products</b></div>
            <div class="col-md-8">
              @foreach($obj->products as $product)
                <a href="{{ route('product.show',$product->id)}}">
                  {{$product->name }}
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
                <a href="{{ route('test',$test->slug)}}">
                  {{$test->name }}
                </a> - <a href="{{ route($app->module.'.show',$obj->id) }}?export=1&test_id={{$test->id}}" class="" data-tooltip="tooltip" data-placement="top" title="Edit">Download Report</a><br>
              @endforeach
            </div>
          </div>
          @endif

          <div class="row mb-2">
            <div class="col-md-4"><b>Usage</b></div>
            <div class="col-md-8"><a href="{{ route('order.index')}}?coupon={{$obj->code}}">{{$obj->count() }}</a></div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Enrolled</b></div>
            <div class="col-md-8">
              @if($obj->enrolled==0)
                    <span class="badge badge-warning">No</span>
                  @else
                    <span class="badge badge-secondary">YES</span>
                  @endif
            </div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Unlimited</b></div>
            <div class="col-md-8">@if($obj->unlimited==0)
                    <span class="badge badge-warning">No</span>
                  @else
                    <span class="badge badge-secondary">YES</span>
                  @endif</div>
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