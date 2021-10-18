<div class="mb-3">
	@if(count($extract->mcq_order)!=0)
	<div class="pl-2 pt-3 pb-3">
		@include('appl.test.attempt.blocks.mcq')
	</div>
	@endif
	
	@if(count($extract->fillup_order)!=0)
	<div class="pl-2 pt-3 pb-3">
		@include('appl.test.attempt.blocks.fillup')
	</div>
	@endif

	

	
</div>