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
          <div class="">{!! $obj->description !!}</div>
          @if($attempt->status==1)
            <span class="badge badge-success">Test Report</span><br>
          @elseif($attempt->status ==-1)
          <hr>
            <span class="badge badge-warning">Under Review</span><br>
            <div class="text-primary mt-4 h4"> The result will be shared in 24 to 48 hours.</div>
          @else
            @if($attempt->t1)
            <a href="{{ route('mockpage.start',$obj->slug)}}" class="btn btn-primary mt-4">Resume Test</a>
            @else
              @if($orders!=null)

                <div class="bg-light p-3  my-3 border">
                  <h5>General Instructions</h5>
                  <hr>
                   <ol>
                      <li>Make sure you are taking the test in <b>Google Chrome</b> browser.</li>
                      <li>Donot minimize the browser or change tabs during the test.</li>
                      <li>Put on your headphones and click on <b>Play Sound</b> button to play a sample sound.<br>
                        <span style="display: none;"><audio id="sound1" src="/audio/sampleclip.mp3" preload="auto" style="display: none;"></audio></span>
            <button onclick="document.getElementById('sound1').play();" class="btn btn-success btn-sm">Play
            Sound</button>
                      </li>
                      <li>Click on the <b>Activate Camera & Microphone</b> button to enable voice recording.<br>
                      <button class="btn btn-success btn-sm activate_microphone" >Activate Camera & Microphone</button>
                      <div id="er_msg" class='text-danger my-3 er_msg'></div>
                      <div class="sc_msg text-success" style='display:none'>Camera & Microphone Activated</div>
                      <div class='msg_er' style='display:none'><a href='https://www.lifewire.com/configure-camera-microphone-setting-in-google-chrome-4103623'>Read the instructions to reset permissions for microphone</a> </div></li>
                    </ol>
                </div>

                @if($auto_activation->lt(\carbon\carbon::now()) && $auto_deactivation->gt(\carbon\carbon::now()))
                  <a href="{{ route('mockpage.start',$obj->slug)}}" class="btn btn-primary btn-lg mt-2">Start Test</a>
                @else
                <div class="alert alert-warning alert-important">
                <h3 class="mb-3"> <div class=""><i class="fa fa-link"></i> The Test  is inactive</div></h3>
                
                <h5 class="display-5 mb-1 "> <div class=""> This test link  will be activated on <span class="text-danger">{{\carbon\carbon::parse($auto_activation)->toDayDateTimeString()}}</span></div></h5>
    <p>and will be deactivated by <span class="text-info">{{\carbon\carbon::parse($auto_deactivation)->toDayDateTimeString()}}</span></p>
    <p><b> Note:</b> You are required to start the test within the above mentioned test window. <br>
    
   </p>
    <p>
    </p>
    <a href="{{ url('/') }}"><button class="btn btn-outline-primary">Home</button></a>

              </div>

                @endif
                
              @else
                 <a href="{{ route('product.view',$obj->products->first()->slug) }}" class="btn btn-success btn-lg mt-4">Activate your product</a>
              @endif
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
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="border rounded p-4">
                  <h5> Reading</h5>
                  <div class="display-4">{{$attempt->t2_score}}</div>
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="border rounded p-4">
                  <h5> Speaking</h5>
                  <div class="display-4">{{$attempt->t3_score}}</div>
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="border rounded p-4">
                  <h5> Writing</h5>
                  <div class="display-4">{{$attempt->t4_score}}</div>
                </div>
              </div>
          </div>

        </div>
      </div>
      @endif

    </div>
  </div> 

@endsection