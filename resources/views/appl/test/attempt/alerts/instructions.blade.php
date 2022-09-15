@extends('layouts.app')
@section('title', 'Test Instructions - '.$test->name)
@section('description', 'These are the test instructions of the test '.$test->name)
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')

@section('content')
<div  class="row ">
  <div class="col-md-12">
    <div class=" bg-light p-3 border mb-3 " style="word-wrap: break-word;
    "><div class="h4 mt-2" style="word-wrap: break-word;
    "><i class="fa fa-bars"></i> {{ $test->name }} - Instructions </div> 
  </div>
  <div class="card">
    <div class="card-body mb-0">
      
      
        @if($audio_permission)
        <div class="mb-3 " style="font-size: 18px;">
          <ol>
            <li>Make sure you are taking the test in <b>Google Chrome</b> browser.</li>
            <li>Donot minimize the browser or change tabs during the test.</li>
            <li>Put on your headphones and click on <b>Play Sound</b> button to play a sample sound.<br>
              <audio id="sound1" src="/audio/sampleclip.mp3" preload="auto"></audio>
  <button onclick="document.getElementById('sound1').play();" class="btn btn-success btn-sm">Play
  Sound</button>
            </li>
            <li>Click on the <b>Activate Camera & Microphone</b> button to enable voice recording.<br>
            <button class="btn btn-success btn-sm activate_microphone" >Activate Camera & Microphone</button>
            <div id="er_msg" class='text-danger my-3 er_msg'></div>
            <div class="sc_msg text-success" style='display:none'>Camera & Microphone Activated</div>
            <div class='msg_er' style='display:none'><a href='https://www.lifewire.com/configure-camera-microphone-setting-in-google-chrome-4103623'>Read the instructions to reset permissions for microphone</a> </div></li>
            <li>If all the above steps are successful, then click on start test<br>
               @if(isset($product->slug))
      <a href="{{ route('test.try',$test->slug)}}?product={{$product->slug}}">
        <button class="btn btn-primary btn-lg"> Start Test</button>
      </a>
      @else
      <a href="{{ route('test.try',$test->slug)}}">
        <button class="btn btn-primary btn-lg"> Start Test</button>
      </a>
      @endif
              
            </li>
          </ol>
          
        </div>
        @else
        <div class="mb-3 " style="font-size: 18px;">
          {!! $test->instructions !!}
         </div>
          @if(isset($product->slug))
          <a href="{{ route('test.try',$test->slug)}}?product={{$product->slug}}">
            <button class="btn btn-primary btn-lg"> Start Test</button>
          </a>
          @else
          <a href="{{ route('test.try',$test->slug)}}">
            <button class="btn btn-primary btn-lg"> Start Test</button>
          </a>
          @endif
        @endif
     
     
    </div>
  </div>
</div>
</div>
@endsection


