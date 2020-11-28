@extends('layouts.bg')
@section('title', $obj->name.' - Score Analytics | First Academy')
@section('content')


<div class="bg-white py-2 mb-4">
<div class="container">
<nav >
 <ol class="breadcrumb bg-white p-0 py-2">
   <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route($app->module.'.index') }}">{{ ucfirst($app->module) }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route($app->module.'.show',$obj->id) }}">{{ $obj->name }} - Score Analytics</a></li>
  </ol>
</nav>

<div class="mb-3">

          <p class="h2 "><i class="fa fa-bar-chart "></i> {{ $obj->name }}  @if(request()->get('today'))
            <span class="badge badge-warning ">Today</span>
            
            @elseif(request()->get('from'))
            <span class="badge badge-warning ">Range</span>
            @elseif(request()->get('all'))
            <span class="badge badge-warning ">ALL</span>
            @else
            <span class="badge badge-warning ">Top 5</span>
            @endif
            <a href="{{ route('test.analytics',$obj->id)}}?all=1" class="btn btn-success float-right">View All</a>
            <a href="{{ route('test.analytics',$obj->id)}}?today=1&all=1" class="btn btn-outline-success float-right mr-2">Today</a>
            <a href="{{ route('test.analytics',$obj->id)}}" class="btn btn-outline-success float-right mr-2">Top 5</a>
            </p>
         

</div>

</div>
</div>


<div class="container">
@include('flash::message')
  <div class="row">
    <div class="col-12 col-md-10">
      <div class="card bg-white mb-3">
        <div class="card-body text-secondary pb-0">
          <p class="h2 mb-0">
            

                 <form method="get" action="{{ route('test.analytics',$obj->id)}}">
            <div class="form-row mb-0">

    <div class="col-5 pb-0 mb-0">
      <label class="sr-only" for="inlineFormInputGroup">FROM</label>
      <div class="input-group mb-0">
        <div class="input-group-prepend">
          <div class="input-group-text">FROM</div>
        </div>
        <input type="text" class="form-control" id="inlineFormInputGroup" name='from' value="@if(request()->get('from')){{request()->get('from')}} @endif" placeholder="YYYY-MM-DD">
      </div>
    </div>
       <div class="col-5 pb-0 mb-0">
      <label class="sr-only" for="inlineFormInputGroup">TO</label>
      <div class="input-group mb-0">
        <div class="input-group-prepend">
          <div class="input-group-text">TO</div>
        </div>
        <input type="text" class="form-control" id="inlineFormInputGroup" name='to' value="@if(request()->get('to')){{request()->get('to')}} @endif" placeholder="YYYY-MM-DD">
      </div>
    </div>
   
    <div class="col-md-2 pb-0">
      <input type="hidden"  name='all' value="1" >
      <button class='btn btn-primary w-100'>Submit</button>
    </div>
  </div>
</form>
            
          </p>
        </div>
      </div>


      <div class="row">
        <div class="col-6 col-md-3 mb-3">
          <div class="card" style="background: #b4e8ca">
            <div class="pl-3 pr-3 pt-3 h5 mb-0">
                Participants
             
            </div>

            <div class="card-body">
              <div class="h1">
                   {{ $users['participants'] }}
                
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card " style="background: #fff8b4">
            <div class="pl-3 pr-3 pt-3 h5 mb-0">
                Highest
             
            </div>
            <div class="card-body">
              <div class="h1">
                @if($users['highest'])
                  {{ $users['highest'] }}
                @else
                  -
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card" style="background: #fff8b4">
            <div class="pl-3 pr-3 pt-3 h5 mb-0">
                Average
             
            </div>
            <div class="card-body">
              <div class="h1">
                @if($users['avg'])
                  {{ $users['avg'] }}
                @else
                  -
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card " style="background: #fff8b4">
            <div class="pl-3 pr-3 pt-3 h5 mb-0">
                Lowest
             
            </div>
            <div class="card-body">
              <div class="h1">
                  {{ $users['lowest'] }}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="">
      @if($users['participants']!=0)
        <div class="table-responsive ">

<table class="table table-bordered bg-white">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      @if($obj->testtype->name!='WRITING' && $obj->testtype->name!='SPEAKING')
      <th scope="col">Score</th>
      @endif
    </tr>
  </thead>
  <tbody class="{{$i=1}}">
    @foreach($score as $k=>$s)
    <tr>
      <th scope="row">{{$i++}}</th>
      <td>
        @if($app->test->status==2 || $app->test->status==3)
        <a href="{{ route('test.analysis',$app->test->slug)}}?session_id={{$k}}&admin=1">{{ $users[$k]['session']['name']}}</a>
        @else
        @if(isset($users[$k]['user']))
        <a href="{{ route('test.analysis',$app->test->slug)}}?user_id={{$users[$k]['user']['id']}}&admin=1">{{ $users[$k]['user']['name']}}</a>
        @endif
        @endif
      </td>

      @if($obj->testtype->name!='WRITING' && $obj->testtype->name!='SPEAKING')
      <td>{{ $s}}</td>
      @endif
    </tr>
    @if($i==6 && !request()->get('all'))
      @break
    @endif
    @endforeach
    
  </tbody>
</table>
        </div>
        @endif
      </div> 
    </div>
    <div class="col-12 col-md-2">
      @include('appl.test.snippets.menu')
    </div>
</div> 
</div>
@endsection