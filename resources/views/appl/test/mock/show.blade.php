@extends('layouts.app')
@section('title', $obj->name.' | First Academy')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')

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
              <a href="{{ route('mockpage',$obj->slug) }}" class="btn btn-outline-secondary" target="_blank" ><i class="fa fa-eye"></i></a>
              <a href="{{ route($app->module.'.show',$obj->id) }}?export=1" class="btn btn-outline-secondary"  ><i class="fa fa-download"></i></a>
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
            <div class="col-md-8">
              <a href="{{ route('mockpage',$obj->slug) }}" target="_blank">{{ $obj->slug }}</a>
            </div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Description</b></div>
            <div class="col-md-8">{!! $obj->description !!}</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Settings</b></div>
            <div class="col-md-8"><pre class=""><code>{{ json_encode(json_decode($obj->settings,true),JSON_PRETTY_PRINT) }}</code></pre></div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Tests</b></div>
            <div class="col-md-8">
                Test 1 - {{$obj->t1}}<br>
                Test 2 - {{$obj->t2}}<br>
                Test 3 - {{$obj->t3}}<br>
                Test 4 - {{$obj->t4}}<br>
            </div>
          </div>
         
         <div class="row mb-2">
            <div class="col-md-4"><b>Status</b></div>
            <div class="col-md-8">@if($obj->status==0)
                    <span class="badge badge-info">Inactive</span>
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

    <div class="col-12">
    @if(count($attempts)!=0)
    <div class="alert alert-warning alert-important important p-1 px-2" >Click on under review to evaluate the responses and assign score</div>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">User</th>
            <th scope="col">T1</th>
            <th scope="col">T2</th>
            <th scope="col">T3</th>
            <th scope="col">T4</th>
            <th scope="col">Score / Band</th>
            <th scope="col">Details</th>
          </tr>
        </thead>
        <tbody>
          @foreach($attempts as $k=>$a)
          <tr>
            <th scope="row">{{$k+1}}</th>
            <td>{{$a->user->name}}</td>
            <td>
              @if($a->t1==1)
                {{lmband($a->t1_score)}}
              @elseif($a->t1==-1)
                <span class="badge badge-warning">Under Review</span>
              @else
                <span class="badge badge-secondary">Not Attempted</span>
              @endif
            </td>
            <td>
              @if($a->t2==1)
                {{rmband($a->t2_score)}}
              @elseif($a->t2==-1)
                <span class="badge badge-warning">Under Review</span>
              @else
                <span class="badge badge-secondary">Not Attempted</span>
              @endif
            </td>
             <td>
              @if($a->t3==1)
              <a href="{{ route('test.analysis',$obj->t3)}}?user_id={{$a->user_id}}&admin=1&mock={{$obj->id}}" target="_blank">
                {{$a->t3_score}}
              </a>
              @elseif($a->t3==-1)
                 <a href="{{ route('test.analysis',$obj->t3)}}?user_id={{$a->user_id}}&admin=1&mock={{$obj->id}}" target="_blank">
                <span class="badge badge-warning">Under Review</span>
                </a>
              @else
                <span class="badge badge-secondary">Not Attempted</span>
              @endif
            </td>
             <td>
              @if($a->t4==1)
              <a href="{{ route('test.analysis',$obj->t4)}}?user_id={{$a->user_id}}&admin=1&mock={{$obj->id}}" target="_blank">
                {{$a->t4_score}}
              </a>
              @elseif($a->t4==-1)
              <a href="{{ route('test.analysis',$obj->t4)}}?user_id={{$a->user_id}}&admin=1&mock={{$obj->id}}" target="_blank">
                <span class="badge badge-warning">Under Review</span>
              </a>
              @else
                <span class="badge badge-secondary">Not Attempted</span>
              @endif
            </td>
            <td>
              @if($a->status==1)
                <span class="badge badge-success">{{
                    overallband($a->t1_score,$a->t2_score,$a->t3_score,$a->t4_score)
                }}</span>
              @elseif($a->status==-1)
                <span class="badge badge-primary">Evaluation Pending</span>
              @else
                <span class="badge badge-secondary">Not Attempted</span>
              @endif
            </td>
            <td>
                <a href="{{ route('mockpage',$obj->slug)}}?user_id={{$a->user_id}}" class="btn btn-outline-primary mr-3 btn-sm" >View Report</a>
                <a href="#" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#exampleModal_{{$a->id}}" data-tooltip="tooltip" data-placement="top" title="Delete" >Delete Attempt</a>
            

                <!-- Modal -->
                <div class="modal fade" id="exampleModal_{{$a->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion - {{$a->user->name}}</h5>
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
                          <input type="hidden" name="user_id" value="{{$a->user->id}}">
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          <button type="submit" class="btn btn-danger">Delete Permanently</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else

    <div class="card">
      <div class="card-body bg-light h5 ">
          No mock attempts recorded
      </div>
    </div>


    @endif
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