<table class="table table-bordered mt-2 @if(strlen($m->a)>30) w-100 @else w-50 @endif" >
  @if($m->a || $m->a==='0')
  <tr>
    <td class="td_option td_{{$m->qno}}_1 option" data-id="{{$m->qno}}" data-option="A" data-group="1">
        @if(!$answers) 
        <input class='input {{$m->qno}}_A {{$m->qno}}_1' type="radio" name="{{$m->qno}}"  data-id="{{$m->qno}}" value="A"/> 
        @endif

        {!! $m->a !!}

        @if($answers)  
          @if($m->answer=='A') <span class="text-primary"><i class="fa fa-check-circle"></i> Answer</span> @endif
        @endif

    </td>
  </tr>
  @endif

  @if($m->b || $m->b==='0')
   <tr>
    <td class="td_option td_{{$m->qno}}_1 option" data-id="{{$m->qno}}" data-option="B" data-group="1">
        @if(!$answers) 
        <input class='input {{$m->qno}}_B {{$m->qno}}_1' type="radio" name="{{$m->qno}}"  data-id="{{$m->qno}}" value="B"/> 
        @endif

        {!! $m->b !!}

        @if($answers)  
          @if($m->answer=='B') <span class="text-primary"><i class="fa fa-check-circle"></i> Answer</span> @endif
        @endif

    </td>
  </tr>
  @endif


  @if($m->c || $m->c==='0')
   <tr>
    <td class="td_option td_{{$m->qno}}_1 option" data-id="{{$m->qno}}" data-option="C" data-group="1">
        @if(!$answers) 
        <input class='input {{$m->qno}}_C {{$m->qno}}_1' type="radio" name="{{$m->qno}}"  data-id="{{$m->qno}}" value="C"/> 
        @endif

        {!! $m->c !!}

        @if($answers)  
          @if($m->answer=='C') <span class="text-primary"><i class="fa fa-check-circle"></i> Answer</span> @endif
        @endif

    </td>
  </tr>
  @endif

  @if($m->d || $m->d==='0')
   <tr>
    <td class="td_option td_{{$m->qno}}_1 option" data-id="{{$m->qno}}" data-option="D" data-group="1">
        @if(!$answers) 
        <input class='input {{$m->qno}}_D {{$m->qno}}_1' type="radio" name="{{$m->qno}}"  data-id="{{$m->qno}}" value="D"/> 
        @endif

        {!! $m->d !!}

        @if($answers)  
          @if($m->answer=='D') <span class="text-primary"><i class="fa fa-check-circle"></i> Answer</span> @endif
        @endif
    </td>
  </tr>
  @endif

  @if($m->e || $m->e==='0')
   <tr>
    <td class="td_option td_{{$m->qno}}_1 option" data-id="{{$m->qno}}" data-option="E" data-group="1">

        @if(!$answers) 
        <input class='input {{$m->qno}}_E {{$m->qno}}_1' type="radio" name="{{$m->qno}}"  data-id="{{$m->qno}}" value="E"/> 
        @endif

        {!! $m->e !!}

        @if($answers)  
          @if($m->answer=='E') <span class="text-primary"><i class="fa fa-check-circle"></i> Answer</span> @endif
        @endif

    </td>
  </tr>
  @endif


  @if($m->f || $m->f==='0')
   <tr>
    <td class="td_option td_{{$m->qno}}_1 option" data-id="{{$m->qno}}" data-option="F" data-group="1">
        @if(!$answers) 
        <input class='input {{$m->qno}}_F {{$m->qno}}_1' type="radio" name="{{$m->qno}}"  data-id="{{$m->qno}}" value="F"/> 
        @endif

        {!! $m->f !!}

        @if($answers)  
          @if($m->answer=='F') <span class="text-primary"><i class="fa fa-check-circle"></i> Answer</span> @endif
        @endif
    </td>
  </tr>
  @endif

  @if($m->g || $m->g==='0')
   <tr>
    <td class="td_option td_{{$m->qno}}_1 option" data-id="{{$m->qno}}" data-option="F" data-group="1">
        @if(!$answers) 
        <input class='input {{$m->qno}}_G {{$m->qno}}_1' type="radio" name="{{$m->qno}}"  data-id="{{$m->qno}}" value="G"/> 
        @endif

        {!! $m->g !!}

        @if($answers)  
          @if($m->answer=='G') <span class="text-primary"><i class="fa fa-check-circle"></i> Answer</span> @endif
        @endif
    </td>
  </tr>
  @endif

  @if($m->h || $m->h==='0')
   <tr>
    <td class="td_option td_{{$m->qno}}_1 option" data-id="{{$m->qno}}" data-option="F" data-group="1">
        @if(!$answers) 
        <input class='input {{$m->qno}}_H {{$m->qno}}_1' type="radio" name="{{$m->qno}}"  data-id="{{$m->qno}}" value="H"/> 
        @endif

        {!! $m->h !!}

        @if($answers)  
          @if($m->answer=='H') <span class="text-primary"><i class="fa fa-check-circle"></i> Answer</span> @endif
        @endif
    </td>
  </tr>
  @endif

  @if($m->i || $m->i==='0')
   <tr>
    <td class="td_option td_{{$m->qno}}_1 option" data-id="{{$m->qno}}" data-option="F" data-group="1">
        @if(!$answers) 
        <input class='input {{$m->qno}}_I {{$m->qno}}_1' type="radio" name="{{$m->qno}}"  data-id="{{$m->qno}}" value="I"/> 
        @endif

        {!! $m->i !!} 

        @if($answers)  
          @if($m->answer=='I') <span class="text-primary"><i class="fa fa-check-circle"></i> Answer</span> @endif
        @endif
    </td>
  </tr>
  @endif

</table>