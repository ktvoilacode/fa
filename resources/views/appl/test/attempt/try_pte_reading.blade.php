@extends('layouts.gre')
@section('title', 'Test - '.$test->name)
@section('description', 'The Test page of '.$test->name)
@section('keywords', 'practice tests, '.$test->name)
@section('content')

@guest
@if(($test->status!=2 && $test->status!=3) && !request()->get('source'))
<div class="alert alert-warning alert-dismissible alert-important fade show" role="alert">
  <strong>Note:</strong> Only registered users can submit the test and view the result. 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
@endguest

<div class="container " style="padding-left:0px;padding-right:0px;">
    <form id="test" class="test" action="{{route('attempt.store',$app->test->slug)}}" method="post" id="write">  
   @if(isset($view))
            <input type="hidden" name="admin" value="1">
            @endif

     
            
    @include('appl.test.attempt.blocks.screen_pte')
    @include('appl.test.attempt.blocks.gremodal')
    </form>
</div>
@endsection
