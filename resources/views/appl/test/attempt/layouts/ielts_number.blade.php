<div class="row question ">
  <div class="col-12 col-md-1" id="{{$f->qno}}">
    <div class="card-text mb-3" ><span class="badge badge-warning h2">{{$f->qno}}</span>
    </div>
  </div>
  <div class="col-12 col-md-11">
    <div class="card-text">
    @if($f->layout == 'default' ||  !$f->layout)
      @if($f->prefix ) {!! $f->prefix !!}  @endif 
      @if($f->answer) 
        @if($answers) <span class="badge badge-primary">{{$f->answer}}</span> 
        @else <input type="text" class="fill input" name="{{$f->qno}}" data-id="{{$f->qno}}" @if(strlen($f->answer)>20) style="width:300px" @endif> @endif
      
      @endif
      @if($f->suffix ){!! $f->suffix !!}@endif
    @elseif($f->layout == 'ielts_number' ||  !$f->layout)
      @if($f->prefix ) {!! $f->prefix !!}  @endif 
      @if($f->answer) 
          @if($answers) <span class="badge badge-primary">{{$f->answer}}</span> 
          @else <input type="text" class="fill input" name="{{$f->qno}}" data-id="{{$f->qno}}" @if(strlen($f->answer)>20) style="width:300px" @endif> @endif
      
      @endif
      @if($f->suffix ){!! $f->suffix !!}@endif
    @elseif($f->layout=='ielts_two_blank')
      @include('appl.test.attempt.layouts.ielts_two_blank') 
    @endif

    </div>
  </div>
</div>
