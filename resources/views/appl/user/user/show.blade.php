@extends('layouts.app')
@include('meta.show')
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
    <div class="col-12 ">
      <div class="card bg-light mb-3">
        <div class="card-body text-secondary">
            
          <p class="h2 mb-0"><i class="fa fa-th "></i> {{ $obj->name }} 

          @can('update',$obj)
            <span class="btn-group float-right" role="group" aria-label="Basic example">
               @if(\auth::user()->admin==1 ||\auth::user()->admin==2)
              <a href="{{ route($app->module.'.edit',$obj->id) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
              @endif
              @if(\auth::user()->admin==1)
              <a href="#" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal" data-tooltip="tooltip" data-placement="top" title="Delete" ><i class="fa fa-trash"></i></a>
              @endif
            </span>
            @endcan
          </p>
        </div>
      </div>

     
      <div class="card mb-4">
        <div class="card-body">

          <div class="row">
            <div class="col-12 col-md-3">
              <img src="{{ asset('images/user.png')}}" class="w-75 mt-4">
            </div>

          <div class="col-12 col-md-9">    

            <div class="row mb-2">
              <div class="col-md-4"><b>ID number</b></div>
              <div class="col-md-8">@if($obj->idno){{ $obj->idno }}@else - @endif </div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4"><b>Name</b></div>
              <div class="col-md-8">{{ $obj->name }}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4"><b>Email</b></div>
              <div class="col-md-8">{{ $obj->email }}

              @if($obj->activation_token==1)
                <span class="text-success"><i class="fa fa-check-circle"></i> </span>
                @else
                <span class="text-secondary"><i class="fa fa-exclamation-circle"></i> </span>
                @endif

                
              </div>
            </div>
           
            <div class="row mb-2">
              <div class="col-md-4"><b>Phone</b></div>
              <div class="col-md-8">{{$obj->phone}}

                @if($obj->sms_token==1)
                <span class="text-success"><i class="fa fa-check-circle"></i> </span>
                @else
                <span class="text-secondary"><i class="fa fa-exclamation-circle"></i> </span>
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-md-4"><b>Auto Password</b></div>
              <div class="col-md-8">@if($obj->auto_password)
                {{$obj->auto_password }}
                @else
                -
                @endif
              </div>
            </div>

            
            
            <div class="row mb-2">
              <div class="col-md-4"><b>Enrolled</b></div>
              <div class="col-md-8">@if($obj->enrolled==0)
                      <span class="badge badge-secondary">NO</span>
                    @elseif($obj->enrolled==1)
                      <span class="badge badge-success">YES</span>
                    @endif</div>
            </div>

            <div class="row mb-2">
              <div class="col-md-4"><b>Status</b></div>
              <div class="col-md-8">@if($obj->status==0)
                      <span class="badge badge-danger">Blocked</span>
                    @elseif($obj->status==1)
                      <span class="badge badge-success">Active</span>
                    @endif</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4"><b>Role</b></div>
              <div class="col-md-8">

                <span class="badge badge-secondary">
                @if($obj->admin==0)
                User
                @elseif($obj->admin==1)
                Administrator
                @elseif($obj->admin==2)
                Employee
                @elseif($obj->admin==4)
                Trainer
                @elseif($obj->admin==3)
                Caller
                @elseif($obj->admin==5)
                Client Admin
                @elseif($obj->admin==6)
                Client Employee
                @elseif($obj->admin==7)
                Client Trainer
                @endif
                </span>
              </div>
            </div>

            @if($obj->user_id)
            <div class="row mb-2">
              <div class="col-md-4"><b>Referral</b></div>
              <div class="col-md-8">
                @if($obj->user_id)
                @if(isset($obj->referral($obj->user_id)->name))
                <a href="{{route('user.show',$obj->user_id)}}">
                    {{ $obj->referral($obj->user_id)->name }}
                </a>
                @endif
                @endif
              </div>
            </div>
            @endif

            <div class="row mb-2">
              <div class="col-md-4"><b>Created At</b></div>
              <div class="col-md-8">{{ ($obj->created_at) ? $obj->created_at->diffForHumans() : '' }}</div>
            </div>
             <div class="row mb-2">
              <div class="col-md-4"><b>Last Login At</b></div>
              <div class="col-md-8">{{ ($obj->lastlogin_at) ? \Carbon\Carbon::parse($obj->lastlogin_at)->diffForHumans() : ' - ' }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-md-4"><b>Comments</b></div>
              <div class="col-md-8">{{ $obj->comment}}</div>
            </div>

            @if($obj->auto_password)
            <div class="row mb-2">
              <div class="col-md-4"><b>Resend Account Details</b></div>
              <div class="col-md-8">
                
                <a href="{{ route('user.show',$obj->id)}}?resend_email=1">
                <button class="btn btn-sm btn-outline-primary"> Send email </button>
                </a>

                <a href="{{ route('user.show',$obj->id)}}?resend_whatsapp=1">
                <button class="btn btn-sm btn-outline-primary"> Send Whatsapp </button>
                </a>
                
                
              </div>
            </div>
            @endif

            
          </div>
        </div>
        </div>
      </div>


@include('appl.'.$app->app.'.'.$app->module.'.products')



      


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