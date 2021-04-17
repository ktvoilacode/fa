
@if(file_exists(public_path().'/storage/'.$test->file) && $test->file)
<link rel='stylesheet' href='{{ asset("css/player.css") }}'>
@endif
<link rel='stylesheet' href='{{ asset("css/try.css") }}'>
<link rel='stylesheet' href='{{ asset("css/test.css") }}'>
<div class="test_container">

@if($score)
	<h1 class="border p-5 py-5">Your score is {{$score}} </h1>
@endif


<form id="ajaxtest" class="test form_{{$app->test->slug}}" action="{{route('attempt.store',$app->test->slug)}}" method="post"> 
@if($testtype->name=='GRAMMAR')
<div class="border">
 <div class="mb-3">
	<div class="part">
		<h3><i class="fa fa-clone"></i> {{ $app->test->name}}</h3>
		@if(strip_tags($app->test->description))
		<p>{!! $app->test->description !!}</p>
		@endif
	</div>
	<div class="bg-white border-top p-4">
		@if(count($app->test->mcq_order)!=0)
		@include('appl.test.attempt.blocks.mcq_grammar')
		@endif

		@if(count($app->test->fillup_order)!=0)
		@include('appl.test.attempt.blocks.fillup_grammar')
		@endif

		<input type="hidden" name="test_id" value="{{ $app->test->id }}">
		<input type="hidden" name="user_id" value="@if(\auth::user()) {{ \auth::user()->id }}@endif ">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="apitest" value="1">
		@if(!$score && !request()->get('answers'))
		<button class="btn btn-success btn-lg ajaxtestsubmit " data-test="{{$app->test->slug}}" type="submit" >Submit</button>
		@endif
	</div>
 </div>
</div>

@elseif($testtype->name=='LISTENING')
	@if(file_exists(public_path().'/storage/'.$test->file) && $test->file)

                @include('appl.test.attempt.blocks.audio')
    @endif

	<div class="border">
	@foreach($test->sections as $s=>$section)
		@include('appl.test.attempt.blocks.section')
	@endforeach


	<input type="hidden" name="test_id" value="{{ $app->test->id }}">
	<input type="hidden" name="user_id" value="@if(\auth::user()) {{ \auth::user()->id }}@endif ">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="apitest" value="1">

	<div class="pr-4 pl-4 pb-4 pt-0">
	@if(!$score && !request()->get('answers'))
		<button class="btn btn-success btn-lg ajaxtestsubmit" data-test="{{$app->test->slug}}" type="submit" >Submit</button>
	@endif
	</div>
	</div>

@elseif($testtype->name=='ENGLISH')
<div class="border ">
	@foreach($app->test->sections as $s=>$section)
	    @include('appl.test.attempt.blocks.section_english')
	@endforeach

	<input type="hidden" name="test_id" value="{{ $app->test->id }}">
	<input type="hidden" name="user_id" value="@if(\auth::user()) {{ \auth::user()->id }}@endif ">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="apitest" value="1">

	<div class="pr-4 pl-4 pb-4 pt-0">
	@if(!$score && !request()->get('answers'))
		<button class="btn btn-success btn-lg ajaxtestsubmit" data-test="{{$app->test->slug}}" type="submit" >Submit</button>
	@endif
	</div>
</div>

@endif
</form>
</div>


<div class="result_container" style="display: none">
<div class="border">
	<div class="result">

	</div>
</div>
</div>
 @include('layouts.script')
