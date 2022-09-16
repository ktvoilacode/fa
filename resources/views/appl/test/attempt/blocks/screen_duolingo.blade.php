<style>
	b{
		color:#636569;
	}

	video {
    width: 50px;
    height: 50px;
    object-fit: cover;
    object-position: center;
}

.camera {
    width: 50px;
    height: 50px;
    overflow: hidden;
}
</style>

<div class="my-0 mt-md-5 py-4 py-md-0" style="box-shadow: 1px 1px 2px 2px #e1e1e1;">
	<div class="bg-white  p-3 py-5 p-md-5 duo_section" data-section="1"  data-testid="{{$app->test->id}}" data-url="{{ route('audio.blob')}}" data-token="{{ csrf_token() }}" data-userid="@if($user->id) {{ $user->id }} @elseif($user->email) {{$user->email }} @endif">

		<div class="float-right rounded camera" style="width:50px;height:50px;margin-top: -15px">
			<video id="video" class="mb-3  w-100" data-token="{{ csrf_token() }}" data-hred="{{ route('image.save')}}" data-count="0" data-c="0" data-username="{{$user->id}}" data-test="{{$test->id}}">Video stream not available.</video>
    <canvas id="canvas" class="" style='display: none' width="50" height="50">
  	</canvas>
		</div>
		
		
		<div class="mb-3 text-secondary" style="font-size: 18px;"><span class="d-block  ">
			@if($app->test->test_time)
	<span class="" id="timer2">{{$app->test->test_time}}:00 </span>
	@else
	<span class="time_count" id="timer3"></span> <span class="time_count d-none" id="timer4"></span>
	@endif&nbsp;</span></div>

		<div class="progress mb-4" style="height:5px;@if(count($test->sections)<2)display:none;@endif">
  <div class="progress-bar {{$m=1}}" role="progressbar" style="width: 0%;background: #ff8159" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
</div>
	@foreach($test->sections as $s=>$section)
	
	<div class="section_data section_data_{{$s+1}}" data-qno="@if(isset($section->mcq_order[0]->qno)) {{$section->mcq_order[0]->qno}} @elseif(isset($section->fillup_order[0]->qno)) {{$section->fillup_order[0]->qno}} @endif" data-section="{{$s+1}}" data-time="{{ $section->seek_time }}" data-layout="@if(isset($section->mcq_order[0]->layout)) {{$section->mcq_order[0]->layout}} @elseif(isset($section->fillup_order[0]->layout)) {{$section->fillup_order[0]->layout}} @endif"   data-question="@if(isset($section->mcq_order[0]->id)) {{$section->mcq_order[0]->id}} @elseif(isset($section->fillup_order[0]->id)) {{$section->fillup_order[0]->id}} @endif"></div>

	
	
	<div class="qblock greblock_{{($s+1)}} p-3" data-qno="{{$s}}"  data-section="{{$s+1}}" data-sno="{{$s+1}}" data-qcount="{{(count($section->mcq_order)+count($section->fillup_order))}}" data-scount="{{count($test->sections)}}" @if($s!=0)style="display:none"@endif   data-question="@if(isset($section->mcq_order[0]->id)) {{$section->mcq_order[0]->id}} @elseif(isset($section->fillup_order[0]->id)) {{$section->fillup_order[0]->id}} @endif">

		@if(count($section->mcq_order)!=0)
			@include('appl.test.attempt.blocks.mcq_english')
		@endif
		@if(count($section->fillup_order)!=0)
			@include('appl.test.attempt.blocks.fillup_english')
		@endif
	</div>
	

	@endforeach

	<hr class="hr">
	

<div class="" style="">
	<div class=" p-0 " style="">
	<div class="row">
		<div class="col-12 col-md-4">
			&nbsp;
			<h5 class="text-danger recording_message
			"  style="display: none;margin-top: -10px"><i class="fa fa-dot-circle-o"></i> Recording </h5>
		</div>
		<div class="col-12 col-md-4">
			&nbsp;
			<canvas class="visualizer" height="40px" width="100px" style="display: none;margin-top: 0px"></canvas>
		</div>

		<div class="col-12 col-md-2">
			<a href="#" class=" gre_prev disabled" data-qno="0" data-duo="1" data-ques-no="1" style="display: none;">
			<div class=" rounded  p-1 ml-1 mb-2 mb-md-0  w-100 btn btn-secondary"  >
					<div class="text-center p-2 "><i class="fa fa-arrow-left"></i> back</div>
				</div>
			</a>
		</div>
		<div class="col-12 col-md-2">
			<div class="row no-gutters">
				<div class="col-12 ">
				<a href="#" class="white disabled record-btn" data-qno="2" data-duo="1" style="
					@if(isset($test->sections[0]->fillup_order[0]->qno)) @if($test->sections[0]->fillup_order[0]->layout=='speak') sample @else display:none; @endif @else display:none; @endif"

					>

					<div class=" rounded  p-1 ml-1 mb-0 mb-md-0 btn-orange-record " >
						<div class="text-center p-2"><i class="fa fa-circle"></i> <span>Record</span>
						</div>
					</div>
				</a>
				
				
				<a href="#" class="white gre_next disabled" data-qno="2" data-duo="1" data-ques-no="1" 
					style="@if(isset($test->sections[0]->fillup_order[0]->layout)) 
							@if($test->sections[0]->fillup_order[0]->layout=='speak')  
								display:none;
							@else 
							apple
							@endif  
							@else
							mango
						    @endif " >
					<div class=" rounded  p-1 ml-1 mb-0 mb-md-0 next-btn btn-orange @if(count($test->sections)<2) btn-submit-duo @endif"  >
						<div class="text-center p-2"><i class="fa fa-arrow-right "></i> <span class="next_text">@if(count($test->sections)<2) Submit @else Next @endif</span>
						</div>
					</div>
				</a>
				</div>
				
				
			</div>
		</div>
	</div>
</div>

	</div>
	</div>
</div>


