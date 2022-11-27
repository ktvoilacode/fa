@extends('layouts.app')
@section('title', $test->name.' - Report')
@section('description', 'Result page of Test')
@section('keywords', 'result page of test, first academy')
@section('content')


<div class="">
  <div class="row">
    <div class="col-12">
      <div class="bg-white p-4 border">
        <div class="row">
          <div class="col-12 col-md-6">
            <h3 class="text-center text-md-left mb-md-4 mt-2  p-4">
              <i class="fa fa-bar-chart"></i> {{ $test->name}} - Report

              <br>
              @if(isset($admin))
              <a href="{{ route('test.show',$test->id)}}">
                <button class="btn btn-sm btn-outline-primary mt-3 ">
                  <i class="fa fa-angle-left"></i> back to Test</button>
              </a>
              @elseif(request()->get('admin'))
              <a href="{{ route('test.analytics',$test->id)}}">
                <button class="btn btn-sm btn-outline-primary mt-3 ">
                  <i class="fa fa-angle-left"></i> back to Test Analytics</button>
              </a>
              @elseif(request()->get('product'))
              <a href="{{ route('product.view',request()->get('product'))}}">
                <button class="btn btn-sm btn-outline-primary mt-3 ">
                  <i class="fa fa-angle-left"></i> back to Product</button>
              </a>
              @else
              <a href="{{ route('test',$test->slug)}}">
                <button class="btn btn-sm btn-outline-primary mt-3 ">
                  <i class="fa fa-angle-left"></i> back to Test</button>
              </a>

              @endif

              @if(\auth::user())
               @if(\auth::user()->isAdmin())
               <a href="{{ route('test.show',$test->id)}}">
                <button class="btn btn-sm btn-outline-secondary mt-3 ">
                  <i class="fa fa-angle-left"></i> Admin Test page</button>
              </a>
               @endif
              @endif

              @if($test->testtype->name=='LISTENING' || $test->testtype->name=='READING')
              <a href="{{ route('test.answers',$test->slug)}}">
                <button class="btn btn-sm btn-primary mt-3 ">
                  <i class="fa fa-bars"></i> View Question Paper & Answers</button>
              </a>
              @endif

             
              @if($test->testtype->name=='DUOLINGO')
              @if(is_numeric($score))
              @if(!is_array($result))
                @if(request()->get('user_id'))
                  <a href="{{ request()->fullUrl()}}&duo_analysis=1">
                @else
                  <a href="{{ request()->fullUrl()}}?duo_analysis=1">
                @endif
                <button class="btn btn-sm btn-success mt-3 ">
                  <i class="fa fa-bars"></i> Detailed Analysis</button>
              </a>
              @endif
              @endif
              @endif
               
            </h3>

          </div>
          <div class="col-12 col-md-6">
             <div class="text-center  mt-3 mb-3 mt-md-0 mb-md-0 float-md-right border bg-light p-3 rounded ">
              
              @if($test->testtype->name=='DUOLINGO')
                <div class="">Score </div>
                @if(!request()->get('session_id'))
                  @if(is_numeric($score))
                    <div class="display-4">{{ $user->duolingoRange($score) }}</div>
                  @else
                   <div class="h3 text-primary">{{ $user->duolingoRange($score) }}</div>
                  @endif
                @else
                <div class="display-4">{{$score}}</div>
                @endif
              
               @elseif($test->testtype->name=='WRITING')
                <div class="">Score </div>
                
                <div class="display-4">{{$score}}</div>
                
                
    
              @else
                <div class="">Score </div>

                @if(!$review)
                <div class="display-4">{{ $score }} / {{ $test->marks}} </div>
                @else
                <div class="h5 badge badge-warning mt-3">Under Review</div>
                @endif
              @endif
            </div>
            @if($band)
            <div class="text-center  mt-3 mb-3 mt-md-0 mb-md-0 float-md-right border bg-light p-3 rounded mr-0 mr-md-4">
              <div class="">&nbsp;&nbsp;&nbsp; Band &nbsp;&nbsp;&nbsp;</div>
              <div class="display-4">{{ $band }} </div>
            </div>
            @elseif($points)
            @if($test->testtype->name!='DUOLINGO')
            <div class="text-center  mt-3 mb-3 mt-md-0 mb-md-0 float-md-right border bg-light p-3 rounded mr-0 mr-md-4">
              <div class="">&nbsp;&nbsp;&nbsp; Points &nbsp;&nbsp;&nbsp;</div>
              <div class="display-4">{{ $points }} </div>
            </div>
            @endif
            @endif
          </div>
        </div>


        @if($test->status==1)
        @if(isset($user->name))
        <div class="bg-light rounded mb-3 p-2 border">
          @if(\auth::user())
            @if(\auth::user()->isAdmin())
              <form method="post" action="{{route('user.test',[$user->id,$test->id])}}">
              <input type="hidden" name="delete" value="1">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-link float-right"><i class="fa fa-trash"></i> Delete Permanently</button>
              </form>
            @endif
          @endif

          Name: <b>{{$user->name}}</b> &nbsp;&nbsp;&nbsp;@if($user->phone) Phone: <b> {{$user->phone}}</b> @endif&nbsp;&nbsp;&nbsp;
        </div>
        @endif
        @endif


        @if($test->status==2)
        @if(isset($user->name))
        <div class="bg-light rounded mb-3 p-2 border">Name: <b>{{$user->name}}</b> &nbsp;&nbsp;&nbsp;@if($user->phone) Phone: <b>{{$user->phone}}</b> @endif&nbsp;&nbsp;&nbsp;
          @if(\auth::user())
            @if(\auth::user()->isAdmin())
              <a href="{{route('test.analysis',$test->slug)}}?delete=1&session_id={{request()->get('session_id')}}"><i class="fa fa-trash"></i> delete</a>
            @endif
          @endif
          <span class="float-md-right">IP: <b>{{$user->ip_address}}</b></span> </div>
        @endif
        @endif

        @if($test->status==3)
        @if(isset($user->name))
        <div class="bg-light rounded mb-3 p-2 border">Name: <b>{{$user->name}}</b> &nbsp;&nbsp;&nbsp;@if($user->phone)Phone: <b>{{$user->phone}}</b>@endif &nbsp;&nbsp;&nbsp;
          @if(\auth::user())
            @if(\auth::user()->isAdmin())
              <a href="{{route('test.analysis',$test->slug)}}?delete=1&session_id={{request()->get('session_id')}}"><i class="fa fa-trash"></i> delete</a>
            @endif
          @endif
          <span class="float-md-right">IP: <b>{{$user->ip_address}}</b></span> </div>
        @endif
        @endif


       @if($test->testtype->name!='DUOLINGO' || request()->get('admin'))
       <form action="{{ url()->current() }}?evaluate=1&admin=1&@if(request()->get('session_id'))session_id={{request()->get('session_id')}} @elseif(request()->get('user_id'))user_id={{request()->get('user_id')}} @endif" method="post">
        @include('appl.test.attempt.blocks.solutions')


        @if(\auth::user())
          @if(\auth::user()->isAdmin() )
            @if( $test->testtype->name=='DUOLINGO'  || $test->testtype->name=='WRITING')
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-primary btn-lg mt-4">Save</button>
            @endif
          @endif
        @endif
        </form>
      @else
        <div class="mt-4">
          <h4 class="pl-3">What does the score mean?</h4>
        {!! $test->duolingoComment($score) !!}
      </div>
      <div class="alert alert-important alert-danger mt-4 p-md-4">
        <h3>Get more with our Expert Evaluation </h3>
         <ol>
          <li>Personalised Comments</li>
<li>High Score Tips</li>
<li>Writing Assessment</li>
<li>Speaking Pointers</li>
<li>Sample Responses</li>
</ol>
        <a  href="https://prep.firstacademy.in/products/det-expert-evaluation" class="btn btn-success ">Buy Now</a>
        
      </div>

      @endif
        

      </div>

      @if(isset($tags))
      @if($tags)
      <div class="mt-4 ">
        @include('appl.test.attempt.alerts.tags')
      </div>
      @endif
      @endif
    </div>
  </div>
</div>

@endsection
