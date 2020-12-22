@extends('layouts.duolingo')
@section('title', 'Test - '.$test->name)
@section('description', 'The Test page of '.$test->name)
@section('keywords', 'practice tests, '.$test->name)
@section('content')

<style>
  .topright{
    position:absolute;
   top:5px;
   right:15px;

  }
  </style>

<div class=" rounded  p-1 ml-1 mb-0 mb-md-0 topright btn btn-secondary btn-sm"  data-toggle="modal" data-target="#test_submit">
            <div class="text-center p-2">End Test
            </div>
          </div>

<div class="container {{$p=1}}" style="padding-left:0px;padding-right:0px;max-width:900px;">


  @guest
    @if($test->status!=3)
    <div class="alert alert-warning alert-dismissible alert-important fade show mt-4" role="alert">
      <strong>Note:</strong> 
      <p>Some features of the Duolingo English Test prevent us from presenting the most optimised experience for non-logged in users.</p>
      <p>We request you to log-in for the best test experience!</p> 
      <a href="{{ route('login')}}" class="mt-3">
        Login now
      </a>
    </div>
    @else
      <form id="test" class="test" action="{{route('attempt.store',$app->test->slug)}}" method="post">  
   @if(isset($view))
            <input type="hidden" name="admin" value="1">
            @endif

    @include('appl.test.attempt.blocks.screen_duolingo')
    @include('appl.test.attempt.blocks.gremodal')
    </form>

    @endif
  @else
    <form id="test" class="test" action="{{route('attempt.store',$app->test->slug)}}" method="post">  
   @if(isset($view))
            <input type="hidden" name="admin" value="1">
            @endif

    @include('appl.test.attempt.blocks.screen_duolingo')
    @include('appl.test.attempt.blocks.gremodal')
    </form>
  @endguest
    
</div>
@endsection
