@extends('layouts.bg')
@section('title', $obj->name.' | Test | First Academy')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')
@section('content')

<div class="bg-white py-2 mb-0">
<div class="container">
<nav >
  <ol class="breadcrumb bg-white p-0 py-2">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route($app->module.'.index') }}">{{ ucfirst($app->module) }}</a></li>
    <li class="breadcrumb-item">{{ $obj->name }}</li>
  </ol>
</nav>

<div class="mb-3">
  @can('update',$obj)
            <span class="btn-group float-right" role="group" aria-label="Basic example">
              <a href="{{ route($app->module.'.edit',$obj->id) }}?category={{$obj->category->name}}&type={{$obj->testtype->name}}" class="btn btn-outline-primary" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="Edit Test"></i> edit</a>
              
              <a href="{{ route($app->module.'.duplicate',$obj->id) }}" class="btn btn-outline-primary" data-tooltip="tooltip" data-placement="top" title="view"  ><i class="fa fa-retweet" data-toggle="tooltip" data-placement="top" title="make a copy of the test"></i> duplicate</a>
              <a href="#" class="btn btn-outline-primary" data-toggle="modal" data-target="#exampleModal"><span data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash" ></i> delete</span></a>
            </span>
            @endcan
          <p class="h2 mb-2 d-inline" >
            <i class="fa fa-file-text-o "></i> 
            {{ $obj->name }} 
          </p>
          

</div>

</div>
</div>
<div class="mb-4 border-bottom">
<div class="container ">
  <div class="py-3">
    <a href="{{ route('test',$obj->slug) }}" class="f20" target="_blank" data-toggle="tooltip" data-placement="top" title="Test Link">
  <i class="fa fa-share-square"></i> {{route('test',$obj->slug)}}</a>&nbsp;
@if($obj->status==0)
                    <span class="badge badge-warning">Inactive</span>
                  @elseif($obj->status==1)
                    <span class="badge badge-success">Active</span>
                    @elseif($obj->status==2)
                    <span class="badge badge-warning">Open</span>
                    @elseif($obj->status==3)
                    <span class="badge badge-warning">Private</span>
                  @endif
</div>
  </div>
</div>
<style>
.block b{color: #22252a}
.f20{ font-size: 18px; }
</style>


<div class="container mb-4">
  @include('flash::message')
  <div class="row">

    <div class="col-12 col-md-3">

    <div class="block mb-4">
      <div class="list-group-item-info   p-3">
        <dl class="row mb-0 no-gutters">
          <dt class="col-sm-5"><b><i class="fa fa-anchor"></i> Slug</b></dt>
          <dd class="col-sm ">{{$obj->slug}}</dd>
        </dl>
        <dl class="row no-gutters mb-0">
          <dt class="col-sm-5"><b><i class="fa fa-th"></i> Category</b></dt>
          <dd class="col-sm">{{$obj->category->name}}</dd>
        </dl>
        <dl class="row no-gutters mb-0">
          <dt class="col-sm-5"><b><i class="fa fa-bars"></i> Type</b></dt>
          <dd class="col-sm">{{$obj->testtype->name}}</dd>
        </dl>
        <dl class="row no-gutters mb-0">
          <dt class="col-sm-5"><b><i class="fa fa-clock-o"></i> Created</b></dt>
          <dd class="col-sm">{{($obj->created_at) ? $obj->created_at->diffForHumans() : ''}}</dd>
        </dl>
      </div>
      <div class="bg-info p-3 pb-2">
        <dl class="row no-gutters mb-0 text-white">
          <dt class="col-sm-5"><b><i class="fa fa-bolt"></i> Level</b></dt>
          <dd class="col-sm mb-0">
              @if($obj->level)
            <div class="">
              <span class="text-white">
                @for($i=$obj->level;$i>0;$i--)
                <i class="fa fa-circle "></i>
                @endfor
              </span>
              <span class="text-white">
                @for($i=(5-$obj->level);$i>0;$i--)
                <i class="fa fa-circle-o "></i>
                @endfor
              </span>
              <span class="float-right">{{$obj->level}}</span>
              
            </span>
            </div>
            @else
            <B>-</B>
            @endif

          </dd>
        </dl>
      </div>
    </div>


    <div class="block mb-4">
      <div class="  p-3" style="background: #ffd3d3;">
        <dl class="row mb-0 no-gutters">
          <dt class="col-sm-5"><b><i class="fa fa-mortar-board"></i> Marks</b></dt>
          <dd class="col-sm "> @if($obj->marks)
                {{ $obj->marks }}
                @else
                -
                @endif</dd>
        </dl>
        <dl class="row no-gutters mb-0">
          <dt class="col-sm-5"><b><i class="fa fa-clock-o"></i> Test Time</b></dt>
          <dd class="col-sm"> @if($obj->test_time)
              {{ $obj->test_time }} min
              @else
              -
              @endif</dd>
        </dl>
        <dl class="row no-gutters mb-0">
          <dt class="col-sm-5"><b><i class="fa fa-dollar"></i> Price</b></dt>
          <dd class="col-sm">@if($obj->price===0)
                <span class="badge badge-warning">FREE</span>
              @elseif($obj->price)
                <i class="fa fa-rupee"></i> {{ $obj->price }} 
              @else
               -
              @endif</dd>
        </dl>
        <dl class="row no-gutters mb-0">
          <dt class="col-sm-5"><b><i class="fa fa-calendar"></i> Validity</b></dt>
          <dd class="col-sm">@if($obj->validity)
                {{ $obj->validity }} m
                @else
                -
                @endif</dd>
        </dl>
      </div>

      <div class="bg-success p-3 pb-2 mt-4" >
        <div class="text-white">
          <div class="mb-3 h3 "><b class="text-white"><i class="fa fa-forumbee"></i> Cache</b></div>
          
              
            @if(isset($obj->cache_updated_at))
        <p> Awesome ! your test is cached.<br>
        <small>Updated:  {{\Carbon\Carbon::parse($obj->cache_updated_at)->diffForHumans() }}</small></p>
        <a href="{{ route('test.cache',$obj->id)}}" class="btn btn-primary">
          Update 
        </a>
        <a href="{{ route('test.cache.delete',$obj->id)}}" class="btn btn-danger">
          Delete Cache
        </a>
        @else
        <p>Cache can speedup performance by 20% so create one now.</p>
        <a href="{{ route('test.cache',$obj->id)}}">
          <button class="btn btn-outline-light">Create Cache</button>
        </a>
        @endif
            
        </div>
      </div>

        <div class="bg-secondary p-3 pb-2 mt-4" >
        <div class="text-white">
          <div class="mb-3 h3 "><b class="text-white"><i class="fa fa-area-chart"></i> Analytics</b></div>
          
         <a href="{{ route('test.analytics',$app->test->id)}} " class="btn btn-outline-light mb-3"><i class="fa fa-user"></i> Student wise &nbsp;-&nbsp;{{$app->test->attemptcount()}}</a> <a href="{{ route('test.qanalytics',$app->test->id)}} " class="btn btn-outline-light "><i class="fa fa-question-circle-o"></i> Question wise</a>
            
        </div>
      </div>

    </div>


    </div>
    <div class="col-12 col-md-7">
      

      <div class="p-3 bg-white mb-4 border-top">
        <div class="">
          <h3 class="d-inline">Test Details</h3><a href="{{ route('test',$obj->slug) }}" class="btn btn-sm btn-outline-success float-right" target="_blank"><i class="fa fa-globe" data-toggle="tooltip" data-placement="top" title="User view - Details Page"></i> User view - buy page</a>
          <p class="text-primary">This information is visible on individual test (buy) page</p>
          @if($obj->details)
          <div class="row">
            <div class="col-12 col-md">
              {!! $obj->details !!}
            </div>
            @if($obj->image)
            <div class="col-12 col-md-4">
               <img 
      src="{{ asset('/storage/test/'.$obj->slug.'_600.jpg') }} " class="image-thumbnail w-100 d-none d-md-block" alt="{{  $obj->name }}">
            </div>
            @endif
          </div>
          
          @else
          - 
          @endif
        </div>
      </div>

     
     
   

      <div class="p-3 bg-white mb-4 border-top" >
        <div class="">
          <h3 class="d-inline">Instructions</h3> <span class="text-muted">(Optional)</span>
          <p class="text-primary">Empty instructions will skip the instruction screen</p>
          @if($obj->instructions)
          {!! $obj->instructions !!}
          @else
          - 
          @endif
        </div>
      </div>

    @if(in_array(strtolower($obj->testtype->name),['listening','grammar','english']) )
    <div class="mb-3 mt-4">
      <h3 class="mb-3  pl-2 d-inline"><i class="fa fa-gg"></i> Preview </h3>
      <a href="{{ route($app->module.'.view',$obj->slug) }}" class="btn btn-outline-primary float-right btn-sm" target="_blank"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="User View - Test Page"></i> User view - test page</a>
    </div>
    <div class="testbox">
      @include('appl.blog.snippets.test')
    </div>
    @endif

     @if(strtolower($obj->testtype->name)=='speaking' || !$obj->testtype)
     <style>
      .speaking b{ color:#f1851a; }
     </style>
        <div class="mb-3 mt-4">
      <h3 class="mb-3  pl-2 d-inline"><i class="fa fa-gg"></i> Preview </h3>
      <a href="{{ route($app->module.'.view',$obj->slug) }}" class="btn btn-outline-primary float-right btn-sm" target="_blank"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="User View - Test Page"></i> User view - test page</a>
    </div>
            <div class=" p-4  speaking rounded mb-4 mb-md-0" style="background: #FFF5EB;border-top:3px solid #f7e0c9">
              <div class="row">
                <div class="col-12 ">
                  @if(strlen(strip_tags(trim($obj->description)))>0)
                  <div class="writing">{!!$obj->description!!}</div>
                  @else
                  <h5>Enter your question</h5>
                  <textarea class="form-control summernote3" name="question" rows=4></textarea>
                  @endif
                </div>

              </div>
            
            </div>

      @endif

      @if(strtolower($obj->testtype->name)=='gre' || strtolower($obj->testtype->name)=='pte' || strtolower($obj->testtype->name)=='reading' || strtolower($obj->testtype->name)=='duolingo')
      <div class="p-3 bg-white mb-4 border-top" >
        <div class="mb-3 mt-4">
      <h3 class="mb-1  pl-1"><i class="fa fa-gg"></i> Preview </h3><small class="mb-3">Due to layout issues reading, gre, duolingo and pte tests preview is not available. Click the user view button to review the layout and questions.</small><br>
      <a href="{{ route($app->module.'.view',$obj->slug) }}" class="btn btn-outline-primary mt-3" target="_blank"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="User View - Test Page"></i> User view - test page</a>


    </div>
    </div>
      @endif

     @if(strtolower($obj->testtype->name)=='writing' || !$obj->testtype)

        <div class="mb-3 mt-4">
      <h3 class="mb-3  pl-2 d-inline"><i class="fa fa-gg"></i> Preview </h3>
      <a href="{{ route($app->module.'.view',$obj->slug) }}" class="btn btn-outline-primary float-right btn-sm" target="_blank"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="User View - Test Page"></i> User view - test page</a>
    </div>
            <div class=" p-4  rounded mb-4 mb-md-0" style="background: #fffadd;border-top:3px solid #efebd2">
              <div class="row">
                <div class="col-12 col-md-2">
                  <img src="{{  url('/').'/images/general/writing.png'}}" class="  mb-4 mx-auto d-block" style="max-width:100px;"/>
                </div>
                <div class="col-12 col-md-10">
                  @if(strlen(strip_tags(trim($obj->description)))>0)
                  <div class="writing">{!!$obj->description!!}</div>
                  @else
                  <h5>Enter your question</h5>
                  <textarea class="form-control summernote3" name="question" rows=4></textarea>
                  @endif
                </div>

              </div>
            
            </div>

      @endif

        @if(strtolower($obj->testtype->name)=='listening' || !$obj->testtype)
      <div class="card bg-light mb-4 mt-4">
        <div class="card-body">
          <h3 class="mb-3">Audio File</h3>
          @if(\Storage::disk('public')->exists($obj->file) && $obj->file )
              
              <form method="post" action="{{route($app->module.'.update',[$obj->id])}}" >
                 <input type="hidden" name="_method" value="PUT">
                 <input type="hidden" name="deletefile" value="1">
                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-sm btn-outline-danger">Delete File</button>
              </form>
              @else
               <span class="text-muted"><i class="fa fa-exclamation-triangle"></i> audio file path not found </span>
              @endif
        </div>
      </div>
      @endif


    

    </div>

    @if(strtoupper($obj->testtype->name)!='SPEAKING' && strtoupper($obj->testtype->name)!='WRITING')
    <div class="col-12 col-md-2">
      @include('appl.test.snippets.menu')
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
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        
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