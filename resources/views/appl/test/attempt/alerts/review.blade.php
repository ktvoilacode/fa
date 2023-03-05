@extends('layouts.app')
@section('title', 'Test Review - '.$test->name)
@section('description', 'The Test Review page of '.$test->name)
@section('keywords', 'practice tests, '.$test->name)
@section('content')
<div  class="row ">
  <div class="col-md-12">
    <div class="bg-white rounded border p-4 mb-3">
    <div class="row no-gutters">
      <div class="col-2 col-md-2">
        <img src="{{ asset('images/general/review.png') }}" class="w-100" />
      </div>
      <div class="col-10 col-md-10">
        <div class="pl-3 pt-md-3">
        <h2>Expert Review</h2>
        Test Name : <span class="text-info">{{ $test->name }}</span> <br>
        User : <span class="text-info">{{ $user->name }}</span>
      </div>
      </div>
    </div>
    </div>
    <div class="card mb-3 mb-md-0">
      <div class="card-body mb-0">
        <div class="">
          
        @if(strip_tags($attempt->answer))
        <div class="bg-light p-3 border mb-4">
        {!! $attempt->answer !!}
        </div>
        @endif
      

        @if(Storage::disk('public')->exists('feedback/feedback_'.$attempt->id.'.pdf'))
          <h4 class="mb-4 mt-2">Your Task Reponse - Evaluation is ready !</h4>
          <a href="{{route('file.download',[$attempt->id])}}?pdf=1">
          <button class="btn btn-success btn-lg"> Download the Evaluation</button>
          </a>
        @endif
        </div>
     </div>
   </div>
 </div>
</div>
@endsection


