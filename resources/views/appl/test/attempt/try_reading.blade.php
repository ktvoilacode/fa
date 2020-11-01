@extends('layouts.reading')
@section('title', 'Reading Test - '.$test->name)
@section('description', 'The Test page of '.$test->name)
@section('keywords', 'practice tests, '.$test->name)
@section('content')

@guest
@if($test->status!=2 && $test->status!=3)
<div class="alert alert-warning alert-dismissible alert-important fade show" role="alert">
  <strong>Note:</strong> Only registered users can submit the test and view the result. 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
@endguest

<div class="" style="padding-left:0px;padding-right:0px;">
    <form id="test" class="test" action="{{route('attempt.store',$app->test->slug)}}" method="post">  

        <div class="row no-gutters {{$sno=1}}">
            <div class="col-12 col-md-6 leftblock" @if(!strip_tags(trim($test->sections[0]->instructions)))style="display: none;"@endif>
                <div class="panel leftpanel p-4 " >
                    <div class="0"></div>
                    @foreach($test->sections as $s=>$section)
                    @include('appl.test.attempt.blocks.section_reading_text')
                    @endforeach
                    <br>
                    <div class="border rounded p-3 bg-light">
                        You are kindly requested to report any errors using the following link

                        <button class="btn btn-danger mt-3" type="button" data-toggle="modal" data-target="#exampleModal2">Report Error</button>
                    </div>
                    <br><br><bR>

                </div>

            </div>
            <div class="col-12 col-md ">
                <div id="a" class="panel rightpanel p-4 {{$sno}}" >
                    <div id="c" class="content"> 
                    <div id="0"></div>
                    @foreach($test->sections as $s=>$section)
                    @include('appl.test.attempt.blocks.section_reading_ques')
                    @endforeach
                    <br><br><br>
                    </div>
                </div>

            </div>
        </div>

        @if(isset($view))
        <input type="hidden" name="admin" value="1">
        @endif
        <input type="hidden" name="test_id" value="{{ $app->test->id }}">
        <input type="hidden" name="user_id" value="@if(\auth::user())
            {{ \auth::user()->id }}
            @endif
            ">
        <input type="hidden" name="product" value="@if($product){{ $product->slug }} @endif">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @include('appl.test.attempt.blocks.qno_reading')

        

        @include('appl.test.attempt.blocks.modal')
    </form>
</div>

@endsection
