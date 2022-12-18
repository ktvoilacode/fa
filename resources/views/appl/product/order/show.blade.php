@extends('layouts.app')
@section('title', $obj->order_id )
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb border bg-light">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route($app->module.'.index') }}">{{ ucfirst($app->module) }}</a></li>
    <li class="breadcrumb-item">{{ $obj->order_id }}</li>
  </ol>
</nav>

@include('flash::message')

  <div class="row">

    <div class="col-md-12">
      <div class="card bg-light mb-3">
        <div class="card-body text-secondary">
          <p class="h2 mb-0"><i class="fa fa-th "></i> {{ $obj->order_id }} 
            @if(\auth::user()->admin==1)
            <a href="{{ route('order.edit',$obj->id) }}">
              <button class="btn btn-primary float-right">Edit</button>
            </a>
            @endif
          </p>
        </div>
      </div>

     
      <div class="card mb-4">
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-4"><b>Order ID</b></div>
            <div class="col-md-8">{{ $obj->order_id }}</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>User </b></div>
            <div class="col-md-8">
              <a href="{{ route('user.show',$obj->user->id) }}">
                {{ $obj->user->name }}
              </a><br>
              {{ $obj->user->phone }}
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Product </b></div>
            <div class="col-md-8">
              @if(isset($obj->product->id))
              <a href="{{ route('product.show',$obj->product->id) }}">
                {{ strip_tags($obj->product->name) }}
              </a>
              @endif
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Txn ID / Coupon / Reference</b></div>
            <div class="col-md-8">
                {{ $obj->txn_id }}
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Payment Mode </b></div>
            <div class="col-md-8">
                {{ $obj->payment_mode }}
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Bank Txn ID  </b></div>
            <div class="col-md-8">
                {{ $obj->bank_txn_id }}
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Ref Name / Client  </b></div>
            <div class="col-md-8">
                {{ $obj->bank_name }}
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Txn Amount  </b></div>
            <div class="col-md-8">
                {{ $obj->txn_amount }}
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Status  </b></div>
            <div class="col-md-8">
                 @if($obj->status==0)
                    <span class="badge badge-warning">Open</span>
                  @elseif($obj->status==1)
                    <span class="badge badge-success">Completed</span>
                  @endif
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Valid till </b></div>
            <div class="col-md-8">{{ date('d M Y', strtotime($obj->expiry))}}</div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Created </b></div>
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