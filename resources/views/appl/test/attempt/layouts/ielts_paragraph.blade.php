<span class="card-text question" id="{{$f->qno}}">
  @if($f->label ) <div><b>{{$f->label }}</b></div>  @endif 

  	@if($f->layout == 'default' ||  !$f->layout )

       @if($f->prefix ) {{$f->prefix }}  @endif 
	     @if($f->answer) <span style="display:inline-block;"><span class="badge badge-warning h2">{{$f->qno}}</span>
       @if($answers) <span class="badge badge-primary">{{$f->answer}}</span> @else 
          <input type="text" class="fill input" name="{{$f->qno}}" data-id="{{$f->qno}}" > @endif
            
        </span>
       <span style="display:inline-block;">
	     @endif
	     @if($f->suffix ){{$f->suffix }}@endif
    @elseif($f->layout=='paragraph')
       @if($f->prefix ) {{$f->prefix }}  @endif 
       @if($f->answer) <span style="display:inline-block;"><span class="badge badge-warning h2">{{$f->qno}}</span>
          @if($answers) <span class="badge badge-primary">{{$f->answer}}</span> @else 
          <input type="text" class="fill input" name="{{$f->qno}}" data-id="{{$f->qno}}" > @endif
          
        </span>
       @endif
       @if($f->suffix ){{$f->suffix }}@endif
    @elseif($f->layout=='ielts_two_blank')
      <span class="badge badge-warning h2">{{$f->qno}}</span>
      @include('appl.test.attempt.layouts.ielts_two_blank') 

    @endif

  
</span>