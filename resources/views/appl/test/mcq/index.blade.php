@extends('layouts.bg')
@include('meta.index')
@section('content')


<div class="bg-white py-2 mb-4">
<div class="container">
<nav >
 <ol class="breadcrumb bg-white p-0 py-2">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin/test')}}">Test</a></li>
    <li class="breadcrumb-item"><a href="{{ route('test.show',$app->test->id)}}">{{$app->test->name}}</a></li>
    <li class="breadcrumb-item">{{ ucfirst($app->module) }}</li>
  </ol>
</nav>

<div class="mb-3">

          <p class="h2 mb-2 d-inline" >
            <i class="fa fa-bars "></i> 
           {{ ucfirst($app->module) }}
          </p>

          <form class="form-inline float-right" method="GET" action="{{ route($app->module.'.index',$app->test->id) }}">

            @can('create',$obj)
            <a href="{{route($app->module.'.layout',$app->test->id)}}" class="btn btn-outline-success   mr-sm-3">
              Create {{ ucfirst($app->module) }}
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


<div class="container">

@include('flash::message')
<div  class="row ">

  <div class="col-12 col-md-10 mb-4">
       
       @if(request()->get('section_id'))
        <div class="bg-warning  p-3 mb-4">
            Section: <b>{{$sections[request()->get('section_id')]->name}}</b>
            Count: <b>{{count($scounter[request()->get('section_id')])}}</b>

        </div>
        @endif

        <div id="search-items ">
         @include('appl.'.$app->app.'.'.$app->module.'.list')
       </div>

 </div>

  <div class="col-12 col-md-2">
      @include('appl.test.snippets.menu')



<div class="list-group  mt-3 mb-3">
  <h4>Sections</h4>
  
  @foreach($sections as $s)
  <a href="{{ route('mcq.index',$app->test->id)}}?section_id={{$s->id}}" class="list-group-item list-group-item-action @if($s->id == request()->get('section_id')) active @endif">
    {{$s->name}} ({{count($scounter[$s->id])}})
  </a>
  @endforeach
  

</div>




    </div>
 
</div>
</div>
@endsection


