@extends('layouts.app')
@include('meta.index')
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb border bg-light">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
    <li class="breadcrumb-item">Test History</li>
  </ol>
</nav>

@include('flash::message')
<div  class="row ">

  <div class="col-md-12">
 
    <div class="card mb-3 mb-md-0">
      <div class="card-body mb-0">
        <nav class="navbar navbar-light bg-light justify-content-between border mb-3">
          <a class="navbar-brand"><i class="fa fa-bars"></i> Test History </a>

          
        </nav>

        <div id="search-items">
        
 @if($objs->total()!=0)
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th scope="col">#({{$objs->total()}})</th>
                <th scope="col">Test </th>
                <th scope="col">User </th>
                <th scope="col">Client </th>
                <th scope="col">Status </th>
                <th scope="col">Created </th>
                
              </tr>
            </thead>
            <tbody>
              @foreach($objs as $key=>$obj)  
              <tr>
                <td scope="row">{{ $objs->currentpage() ? ($objs->currentpage()-1) * $objs->perpage() + ( $key + 1) : $key+1 }}</td>
                <td>
                  <a href="{{ route('mock.show',$obj->mock_id) }}">{{ $mocks[$obj->mock_id]->name }}</a>
                </td>
               <td>{{$obj->user->name}}</td>
               <td>{{$mocks[$obj->mock_id]->client_slug}}</td>
               <td>
                @if($mocks[$obj->mock_id]->status==1)
                <span class="badge badge-success">
                   Evaluated
                </span>
              @elseif($mocks[$obj->mock_id]->status==-1)
                <span class="badge badge-primary">Evaluation Pending</span>
              @else
                <span class="badge badge-secondary">Not Attempted</span>
              @endif
              </td>
               <td>{{ ($obj->created_at) ? $obj->created_at->diffForHumans() : '' }}</td>
              </tr>
              @endforeach      
            </tbody>
            
          </table>
        </div>
        @else
        <div class="card card-body bg-light">
          No {{ $app->module }} found
        </div>
        @endif
        <nav aria-label="Page navigation  " class="card-nav @if($objs->total() > config('global.no_of_records'))mt-3 @endif">
        {{$objs->appends(request()->except(['page','search']))->links()  }}
      </nav>

       </div>

     </div>
   </div>
 </div>
 
</div>

@endsection


