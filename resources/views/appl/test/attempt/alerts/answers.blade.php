@extends('layouts.clean')
@section('title', 'Answers - '.$test->name)
@section('description', 'Answers -'.$test->name)
@section('keywords', 'practice tests, '.$test->name)
@section('content')



<div class="container" style="padding-left:0px;padding-right:0px;">

    <div class="row p-0 m-0">
        <div class="col-12 ">
            
            <div class="mb-0 border m-4 p-3">
            @if(file_exists(public_path().'/storage/'.$test->file) && $test->file)
                @include('appl.test.attempt.blocks.audio')
            @endif
            
            @foreach($test->sections as $s=>$section)
                @include('appl.test.attempt.blocks.section')
            @endforeach
            </div>

        </div>
       
        
    </div>
  
</div>

@endsection
