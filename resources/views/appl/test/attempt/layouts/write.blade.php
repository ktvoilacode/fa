
@if($f->label ) <div class="pt-3 pb-5 text-center duo-heading"><b>Respond to the question in at least 50 words</b></div> 
@elseif($f->prefix)
<div class="pt-3 pb-5 duo-heading">{!!$f->prefix!!}</div>
@else
<div class="pt-3 pb-5 text-center duo-heading"><b>Write one or more sentences that describe the image</b></div>
@endif
<div class="row question ">
<div class="col-12 @if($f->label) col-md-5 @elseif($f->prefix) @else col-md-3 @endif">
@if($f->label ) <div class="h5 mt-3">{{$f->label }}</div>  
@elseif($f->prefix)
@else
<div class="text-center">
<div class="img_container float-md-right mb-3" style="">
	<img src="{{ asset('/storage/extracts/'.$f->id.'_q_300.jpg') }}" class="w-100" style="border-radius:5px;">
</div>
</div>
@endif 
</div>
  <div class="col-12 col-md">
  	<div class="mb-4 ">
	

	<textarea class="summernote4" name="{{$f->qno}}" data-id="{{$f->qno}}" row="8"></textarea>
	@if($f->label || $f->prefix) 
	<div class=" mt-1 text-secondary"><span class="f-word-count wc_{{$f->qno}}">0</span> words</div>
	@endif

</div>
  </div>
</div>