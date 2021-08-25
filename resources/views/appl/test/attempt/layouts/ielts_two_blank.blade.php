@if($f->prefix ) {!! $f->prefix !!}  @endif 

			@if($answers) 
				@if(isset($result))
		            @if($result)
						<input type="text" class="fill input" name="{{$f->qno}}[]" data-id="{{$f->qno}}" value="{{$result[$f->qno]['response']}}">
		              @if($result[$f->qno]['accuracy'])
		                <span class="text-success"><i class="fa fa-check-circle"></i> correct</span>
		              @else
		                <span class="text-danger"><i class="fa fa-times-circle"></i> incorrect</span>
		              @endif
		            @endif
		        @else
				<span class="badge badge-primary">{{$f->answer}}</span> 
				@endif
            @else 
          	@if($f->answer) <input type="text" class="fill input" name="{{$f->qno}}[]" data-id="{{$f->qno}}" >
				<?php echo get_string_between($f->answer,'[',']') ?>
			<input type="text" class="fill input" name="{{$f->qno}}[]" data-id="{{$f->qno}}" >
			@endif
           @endif


@if($f->suffix ){!! $f->suffix !!}@endif