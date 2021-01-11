@if($f->prefix ) {!! $f->prefix !!}  @endif 

			@if($answers) <span class="badge badge-primary">{{$f->answer}}</span> 
            @else 
          	@if($f->answer) <input type="text" class="fill input" name="{{$f->qno}}[]" data-id="{{$f->qno}}" >
				<?php echo get_string_between($f->answer,'[',']') ?>
			<input type="text" class="fill input" name="{{$f->qno}}[]" data-id="{{$f->qno}}" >
			@endif
           @endif


@if($f->suffix ){!! $f->suffix !!}@endif