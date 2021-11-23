@extends('layouts.bg')
@section('title', 'Tests | First Academy')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')
@section('content')




<div class="bg-white py-2 mb-4">
<div class="container">
<nav >
  <ol class="breadcrumb bg-white p-0 py-2">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
    <li class="breadcrumb-item">{{ ucfirst($app->module) }}</li>
  </ol>
</nav>

<div class="mb-3">
  <div class="row" >
    <div class="col-12 col-md-6">
      
      <div class="h2"><i class="fa fa-check-square-o "></i> Tests<div class="h5 text-secondary d-inline"> ({{$objs->total()}})</div>
    </div>
    </div>
    <div class="col-12 col-md-6">
      @can('create',$obj)
            
            <a href="{{route($app->module.'.index')}}?refresh=1" class="btn btn-outline-secondary float-right mr-sm-3">
              Refresh Cache
            </a>
            <a href="{{ route('test.createlist')}}" class="btn btn-outline-success mr-3 float-right">Create </a>
            @endcan
  <form class="form float-right mt-0 pt-0" method="GET" action="{{ route($app->module.'.index') }}">

            
            <div class="input-group ">
              <div class="input-group-prepend ">
                <div class="input-group-text"><i class="fa fa-search"></i></div>
              </div>
              <input class="form-control mr-sm-3"  name="item" autocomplete="off" type="search" placeholder="Search" aria-label="Search" 
              value="{{Request::get('item')?Request::get('item'):'' }}">
            </div>
            
          </form>
        </div>
          </div>

          
          
         

</div>

</div>
</div>


<div class="container">

@include('flash::message')
<div  class="row ">
  
  <div class="col-md-12">
 
        <div id="search-items">
         @include('appl.'.$app->app.'.'.$app->module.'.list')
       </div>

 </div>
 
</div>
</div>
@endsection


