@extends('layouts.bg')
@include('meta.index')
@section('content')

<div class=" mb-4 bgblue bdbblue" >
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb  pl-0 mb-1 bgblue" >
        <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
        <li class="breadcrumb-item">{{ ucfirst($app->module) }}</li>
      </ol>
    </nav>
    <div class="row mb-3">
      <div class="col-12 col-md-8">
        <h3 class="mb-3"><i class="fa fa-bars"></i> {{ ucfirst($app->module) }}</h3>
      </div>
      <div class="col-12 col-md-4">
        <form class="form-inline" method="GET" action="{{ route($app->module.'.index') }}">
            @can('create',$obj)
            <a href="{{route($app->module.'.create')}}">
              <button type="button" class="btn btn-success mb-2 my-sm-3 my-md-0 mr-sm-3">Create {{ ucfirst($app->module) }}</button>
            </a>
            @endcan
            <div class="input-group ">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-search"></i></div>
              </div>
              <input class="form-control " id="search" name="item" autocomplete="off" type="search" placeholder="Search" aria-label="Search" 
              value="{{Request::get('item')?Request::get('item'):'' }}">
            </div>
          </form>
      </div>
    </div>
  </div>
</div>


@include('flash::message')
<div  class="container">
  <div class="p-4  bg-white mb-3" style="box-shadow: 1px 1px 1px 1px #eee;">
    <div id="search-items">
      @include('appl.'.$app->app.'.'.$app->module.'.list')
    </div>
 </div>
</div>

@endsection


