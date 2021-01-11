
  <div class="row">
    <div class="col-3 col-md-3 col-lg-2">
      <div class="op">
        @if(!$answers) 
        <input class
        ='input' type="radio" name="{{$m->qno}}"  data-id="{{$m->qno}}" value="A"/>
        @endif

         <span class="mt-2">A</span></div>
    </div>
    <div class="col-9 col-md-9 col-lg-10">
      <div class="option">{!! $m->a !!}
        @if($answers)  
          @if($m->answer=='A') <span class="text-primary"><i class="fa fa-check-circle"></i> Answer</span> @endif
        @endif
      </div>
  </div>
</div>
@if($m->b )
<div class="row">
    <div class="col-3 col-md-3 col-lg-2">
      <div class="op">
        @if(!$answers) 
        <input class
        ='input' type="radio" name="{{$m->qno}}"  data-id="{{$m->qno}}" value="B"/> 
        @endif

        <span class="mt-2">B</span></div>
    </div>
    <div class="col-9 col-md-9 col-lg-10">
      <div class="option">{!! $m->b !!}
        @if($answers)  
          @if($m->answer=='B') <span class="text-primary"><i class="fa fa-check-circle"></i> Answer</span> @endif
        @endif
      </div>
  </div>
</div>
@endif 

@if($m->c )
<div class="row">
    <div class="col-3 col-md-3 col-lg-2">
      <div class="op">
        @if(!$answers) 
        <input class
        ='input' type="radio" name="{{$m->qno}}"  data-id="{{$m->qno}}" value="C"/> 
        @endif

        <span class="mt-2">C</span></div>
    </div>
    <div class="col-9 col-md-9 col-lg-10">
      <div class="option">{!! $m->c !!}

        @if($answers)  
          @if($m->answer=='C') <span class="text-primary"><i class="fa fa-check-circle"></i> Answer</span> @endif
        @endif

      </div>
  </div>
</div>
@endif
@if($m->d )
<div class="row">
    <div class="col-3 col-md-3 col-lg-2">
      <div class="op">
        @if(!$answers) 
        <input class
        ='input' type="radio" name="{{$m->qno}}"  data-id="{{$m->qno}}" value="D"/> 
        @endif
        <span class="mt-2">D</span></div>
    </div>
    <div class="col-9 col-md-9 col-lg-10">
      <div class="option">{!! $m->d !!} @if($answers)  
          @if($m->answer=='D') <span class="text-primary"><i class="fa fa-check-circle"></i> Answer</span> @endif
        @endif</div>
  </div>
</div>
@endif