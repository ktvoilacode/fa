
@if(file_exists(public_path().'/storage/'.$test->file) && $test->file)
<link rel='stylesheet' href='{{ asset("css/player.css") }}'>
@endif

@if(request()->get('style_bootstrap'))
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
@endif

@if(request()->get('style_test'))
<link rel='stylesheet' href='{{ asset("css/try.css") }}'>
<link rel='stylesheet' href='{{ asset("css/test.css") }}'>
@endif
<div class="test_container">

@if($score)
	<h1 class="border p-5 py-5">Your score is {{$score}} </h1>
@endif


<form id="ajaxtest" class="test form_{{$app->test->slug}}" action="{{route('attempt.store',$app->test->slug)}}" method="post"> 
@if($testtype->name=='GRAMMAR')
<div class="@if(request()->get('layout')=='fa') border @endif">
 <div class="mb-3">
 	@if(request()->get('layout')=='fa')
	<div class="part">
		<h3><i class="fa fa-clone"></i> {{ $app->test->name}}</h3>
		@if(strip_tags($app->test->description))
		<p>{!! $app->test->description !!}</p>
		@endif
	</div>
	@endif
	<div class="">
		@if(count($app->test->mcq_order)!=0)
		@include('appl.test.attempt.blocks.mcq_grammar')
		@endif

		@if(count($app->test->fillup_order)!=0)
		@include('appl.test.attempt.blocks.fillup_grammar')
		@endif

		<input type="hidden" name="test_id" value="{{ $app->test->id }}">
		<input type="hidden" name="user_id" value="@if(\auth::user()) {{ \auth::user()->id }}@endif ">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="apitest" value="1"><input type="hidden" name="answer_button" class="answer_button" value="@if(request()->get('answer_button')) 1 @else 0 @endif">


		@if(!$score && !request()->get('answers'))
		<div class="btn btn-success btn-lg ajaxtestsubmit" data-test="{{$app->test->slug}}" >Submit</div>
		@endif
	</div>
 </div>
</div>

@elseif($testtype->name=='LISTENING')
	@if(file_exists(public_path().'/storage/'.$test->file) && $test->file)

                @include('appl.test.attempt.blocks.audio')
    @endif

	<div class="@if(request()->get('layout')=='fa') border @endif">
	@foreach($test->sections as $s=>$section)
		@include('appl.test.attempt.blocks.section')
	@endforeach


	<input type="hidden" name="test_id" value="{{ $app->test->id }}">
	<input type="hidden" name="user_id" value="@if(\auth::user()) {{ \auth::user()->id }}@endif ">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="apitest" value="1"><input type="hidden" name="answer_button" class="answer_button" value="@if(request()->get('answer_button')) 1 @else 0 @endif">


	<div class="pr-4 pl-4 pb-4 pt-0">
	@if(!$score && !request()->get('answers'))
		<div class="btn btn-success btn-lg ajaxtestsubmit" data-test="{{$app->test->slug}}" >Submit</div>
	@endif
	</div>
	</div>

@elseif($testtype->name=='ENGLISH')
<div class="@if(request()->get('layout')=='fa') border @endif">
	@foreach($app->test->sections as $s=>$section)
	    @include('appl.test.attempt.blocks.section_english')
	@endforeach

	<input type="hidden" name="test_id" value="{{ $app->test->id }}">
	<input type="hidden" name="user_id" value="@if(\auth::user()) {{ \auth::user()->id }}@endif ">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="apitest" value="1">
	<input type="hidden" name="answer_button" class="answer_button" value="@if(request()->get('answer_button')) 1 @else 0 @endif">

	<div class="pr-4 pl-4 pb-4 pt-0">
	@if(!$score && !request()->get('answers'))
		<div class="btn btn-success btn-lg ajaxtestsubmit" data-test="{{$app->test->slug}}" >Submit</div>
	@endif
	</div>
</div>

@endif
</form>
</div>

@if(isset($result))
			@if($result)
				<div class="test_return_data"  data-answer_button="@if(request()->get('answer_button')) 1 @else 0 @endif"></div>
			@endif
		@endif

<div class="result_container" style="display: none">
<div class="border">
	<div class="result">

	</div>
</div>
</div>
 @include('layouts.script')
