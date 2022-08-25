@extends('layouts.meta')
@section('title', $obj->name.' - '.getenv('APP_NAME'))
@section('description', $obj->description)
@section('keywords', $obj->name)

@section('content')



@include('flash::message')

  <div class="row">
    <div class="col-md-12">

      <div class="card mb-4">
        <div class="card-body">
          <h3 class="mb-0">{{ $obj->name }}</h3>
          <div class="">{{$obj->description}}</div>
          @if($attempt->status==1)
            <span class="badge badge-success">Test Report</span><br>
          @elseif($attempt->status ==-1)
            <span class="badge badge-warning">Under Review</span><br>
            <div class="text-info mt-4"> The result will be shared in 24 to 48 hours.</div>
          @else
            @if($attempt->t1)
            <a href="{{ route('mockpage.start',$obj->slug)}}" class="btn btn-primary mt-4">Resume Test</a>
            @else
            <a href="{{ route('mockpage.start',$obj->slug)}}" class="btn btn-success mt-4">Start Test</a>
            @endif

          @endif
        </div>
      </div>

      @if($attempt->status==1)
      <div class="card mb-4">
        <div class="card-body">

          <div class="row">
              <div class="col-12 col-md-3">
                <div class="border rounded p-4">
                  <h5> Listening</h5>
                  <div class="display-4">{{$attempt->t1_score}}</div>
                  <div class=""><a href="">view report</a></div>
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="border rounded p-4">
                  <h5> Reading</h5>
                  <div class="display-4">{{$attempt->t2_score}}</div>
                  <div class=""><a href="">view report</a></div>
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="border rounded p-4">
                  <h5> Speaking</h5>
                  <div class="display-4">{{$attempt->t3_score}}</div>
                  <div class=""><a href="">view report</a></div>
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="border rounded p-4">
                  <h5> Writing</h5>
                  <div class="display-4">{{$attempt->t4_score}}</div>
                  <div class=""><a href="">view report</a></div>
                </div>
              </div>
          </div>

        </div>
      </div>
      @endif

    </div>
  </div> 

@endsection