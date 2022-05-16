
<div class="sticky-top p-2   mb-3 plyr-bg {{ url('/').'/storage/'.$test->file }}" >
	<div class="p-1">
		<h5><span class="trackname">{{$test->name}}
		</span> <span class="float-right"><span class="undo btn btn-outline-light btn-sm backward">10 <i class="fa fa-undo"></i></span><span class="undo btn btn-outline-light btn-sm ml-2 forward"><i class="fa fa-repeat"></i>10</span></span></h5>
	</div>
	<div class="">
		<audio >
		<source id="player"  src="{{ url('/').'/storage/'.$test->file }}" type="audio/mp3">
		</audio>
	</div>
</div>
