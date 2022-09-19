<div class="mb-3">
	<div class="part ">
	<h3><i class="fa fa-clone"></i> {{ $section->name}}
		@if($section->seek_time!=-1 && !$hide_player)
		<button class="btn play play_s{{$section->seek_time}} btn-outline-primary btn-sm mt-2 mt-sm-0" data-seek="{{ $section->seek_time }}" onclick="this.blur();" type="button">Play Instructions</button>
		@endif
	</h3>
	@if(strip_tags($section->instructions))
	<p>{!! $section->instructions !!}</p>
	@endif
	</div>
	
	@foreach($section->extracts as $k=>$extract )
		@include('appl.test.attempt.blocks.extract')
	@endforeach
</div>