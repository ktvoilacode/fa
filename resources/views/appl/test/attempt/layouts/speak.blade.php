
@if(\Storage::disk('public')->exists('extracts/'.$f->id.'_q.mp3') )
<div class="pt-3 pb-5 text-center duo-heading"><b>Speak the answer to the question you hear</b></div>

@elseif(\Storage::disk('public')->exists('extracts/'.$f->id.'_q_300.jpg')) 
<div class="pt-3 pb-5 text-center duo-heading"><b> Describe aloud the image below</b></div>
@elseif($f->label ) <div class="pt-3 pb-5 text-center duo-heading"><b>Record yourself saying the statement below:</b></div> @elseif($f->prefix)
	@if($f->suffix)
	<div class="pt-3 pb-5 text-center duo-heading"><b>{{$f->suffix}}</b></div>
	@else
	<div class="pt-3 pb-5 text-center duo-heading"><b>Speak for atleast 30 seconds to the below question</b></div>
	@endif

@elseif($f->suffix)
<div class="pt-3 pb-5 text-center duo-heading"><b>Speak the answer to the question</b></div>
@endif

<div style="max-width: 600px;margin: 0px auto;">
<div class="row question ">
	@if($f->label && !\Storage::disk('public')->exists('extracts/'.$f->id.'_q.mp3') && !\Storage::disk('public')->exists('extracts/'.$f->id.'_q_300.jpg'))
<div class="col-3 col-md-2">
	<img src="{{ asset('images/tests/record.png') }}" class="w-100" />
</div>
@endif
<div class="col ">
@if(\Storage::disk('public')->exists('extracts/'.$f->id.'_q.mp3') )

<div id="@if(isset($s))playerContainer_{{$s}}@else playerContainer_0 @endif" class="cplayer mb-3" data-src="{{ asset('/storage/extracts/'.$f->id.'_q.mp3') }}"></div>
    <span class=""></span>
@elseif(\Storage::disk('public')->exists('extracts/'.$f->id.'_q_300.jpg')) 
<div class="text-center" style="margin:auto ;">

<div class=" mb-3" style="text-align: center;">
	<img src="{{ asset('/storage/extracts/'.$f->id.'_q_300.jpg') }}" class="" style="max-width:160px;border-radius:5px;">
</div>
</div>
@elseif($f->label ) <div class="h5 mt-3">{{$f->label }}</div> 
@elseif($f->prefix)
<div class="border rounded p-3 mt-3">{!! $f->prefix !!}</div> 

@elseif($f->suffix)
<div class="border rounded p-3 mt-3">{!! $f->suffix!!}</div> 

@endif 
</div>
  
</div>
</div>