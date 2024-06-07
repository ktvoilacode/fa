@extends('layouts.bg')
@include('meta.index')
@section('content')

<div class=" mb-4 bgblue bdbblue">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb  pl-0 mb-1 bgblue">
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
          @if(subdomain()=='prep')
          <div class="dropdown">
            <button class="btn btn-success dropdown-toggle mr-3" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Create
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="{{route($app->module.'.create')}}?client_slug=prep">Prep</a>
              @if(isset($client_list ))
              @foreach($client_list as $c)
              <a class="dropdown-item" href="{{route($app->module.'.create')}}?client_slug={{$c->slug}}">{{$c->name}}</a>
              @endforeach
              @endif
            </div>
          </div>
          @else
          <a href="{{route($app->module.'.create')}}?client_slug={{subdomain()}}" class="btn btn-success mr-3">Create</a>
          @endif
          <div class="input-group ">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fa fa-search"></i></div>
            </div>
            <input class="form-control " id="search" name="item" autocomplete="off" type="search" placeholder="Search" aria-label="Search" value="{{Request::get('item')?Request::get('item'):'' }}">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<div class="container">
  @include('flash::message')
  <div class="p-4  bg-white mb-3" style="box-shadow: 1px 1px 1px 1px #eee;">
    <div id="search-items">
      @include('appl.'.$app->app.'.'.$app->module.'.list')
    </div>
  </div>
</div>



@endsection