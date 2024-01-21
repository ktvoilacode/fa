@extends('layouts.clean')
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
            </h3>

          </div>
          <div class="col-12 col-md-6">
             <div class="text-center  mt-3 mb-3 mt-md-0 mb-md-0 float-md-right border bg-light p-3 rounded ">
              
              @if($test->testtype->name=='DUOLINGO')
                <div class="">Score Range</div>
                <div class="display-4">{{ $test->duolingoRange($score) }}</div>
              @else
                <div class="">Score </div>

                @if(!$review)
                <div class="display-4">{{ $score }} @if($test->marks) / {{ $test->marks}} @endif </div>
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

    
        @if($test->status==2)
        @if(isset($user->name))
        <div class="bg-light rounded mb-3 p-2 border">Name: <b>{{$user->name}}</b> &nbsp;&nbsp;&nbsp;Phone: <b>{{$user->phone}}</b> &nbsp;&nbsp;&nbsp;
          @if(\auth::user())
            @if(\auth::user()->isAdmin())
              <a href="{{route('test.analysis',$test->slug)}}?delete=1&session_id={{request()->get('session_id')}}"><i class="fa fa-trash"></i> delete</a>
            @endif
          @endif
          <span class="float-md-right">IP: <b>{{$user->ip_address}}</b></span> </div>
        @endif
        @endif

        
      @if($test->testtype->name!='DUOLINGO' || request()->get('admin'))
        @include('appl.test.attempt.blocks.solutions')
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
