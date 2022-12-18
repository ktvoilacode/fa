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
      <div class="col-12 col-md-10">
        <h3 class="mb-3"><i class="fa fa-bars"></i> {{ ucfirst($app->module) }}</h3>
      </div>
      <div class="col-12 col-md-2">
        <form class="form-inline" method="GET" action="{{ route($app->module.'.index') }}">
            @if(subdomain()=='prep')
            <div class="dropdown float-right">
              <button class="btn btn-success dropdown-toggle mr-3" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Create
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{route($app->module.'.create')}}?client_slug=prep">Prep</a>
                @foreach($client_list as $c)
                <a class="dropdown-item" href="{{route($app->module.'.create')}}?client_slug={{$c->slug}}">{{$c->name}}</a>
                @endforeach
              </div>
            </div>
            @else
             
            @endif
         
          </form>
      </div>
    </div>
  </div>
</div>


<div  class="container">
  @include('flash::message')
  @if($credits['unused']<0)
  <div class="alert alert-warning alert-important border border-warning"> 
    @if($credits['unused']<-10000)
      Balance is below threshold. User accounts are locked. Add credits to unlock the portal.
    @elseif($credits['unused']>-10000 && $credits['unused']<0)
      Balance is low. Add credits now.
    @endif
  </div>
  @endif

  <div class="row mb-3">
    <div class="col-6 col-md-4"> 
      <div class="alert alert-success alert-important border p-4 rounded mb-0">
        <h4>Balance</h4>
        <div class="display-4">{{$credits['unused']}}</div>
      </div>
    </div>
    <div class="col-6 col-md-4"> 
      <div class="bg-light border p-4 rounded">
        <h4>Used Credits</h4>
        <div class="display-4">{{ $credits['used']}}</div>
      </div>
    </div>
    <div class="col-6 col-md-4"> 
      <div class="bg-light border p-4 rounded">
        <h4>Total Credits</h4>
        <div class="display-4">{{ $credits['total']}}</div>
      </div>
    </div>
  </div>

  <div class="p-4  bg-white mb-3" style="box-shadow: 1px 1px 1px 1px #eee;">
    <div id="search-items">
      @include('appl.'.$app->app.'.'.$app->module.'.list')
    </div>
 </div>
</div>



@endsection


