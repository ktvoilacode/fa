<table class="table table-bordered mt-4 max_options_{{ str_replace(' ','',str_replace('-','',$m->qno))}} @if(strlen($m->a)>30) w-100 @else w-50 @endif" data-opt={{ (strlen(trim(str_replace(' ','',$m->answer)))-1) }} data-lastselect="" data-counter="0">
  
      @if($m->a || $m->a===0)

      <tr>
      <td class="td_option td_{{ str_replace(' ','',str_replace('-','',$m->qno))}}_1 option" data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" data-option="A" data-group="1">
        <input class='input {{ str_replace(' ','',str_replace('-','',$m->qno))}}_A {{ str_replace(' ','',str_replace('-','',$m->qno))}}_1' type="checkbox" name="{{ $m->qno}}[]"  data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" value="A"/> {!! $m->a !!}
      </td>
      </tr>
      @endif

      @if($m->b || $m->b===0)
      <tr>
      <td class="td_option td_{{ str_replace(' ','',str_replace('-','',$m->qno))}}_2 option" data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" data-option="B" data-group="2">
        <input class='input {{ str_replace(' ','',str_replace('-','',$m->qno))}}_B {{ str_replace(' ','',str_replace('-','',$m->qno))}}_2' type="checkbox" name="{{ $m->qno}}[]"  data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" value="B"/> {!! $m->b !!}
      </td>
      </tr>
      @endif

      @if($m->c || $m->c===0)
      <tr>
      <td class="td_option td_{{ str_replace(' ','',str_replace('-','',$m->qno))}}_3 option" data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" data-option="C" data-group="3">
        <input class='input {{ str_replace(' ','',str_replace('-','',$m->qno))}}_C {{ str_replace(' ','',str_replace('-','',$m->qno))}}_3' type="checkbox" name="{{ $m->qno}}[]"  data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" value="C"/> {!! $m->c !!}
      </td>
      </tr>
      @endif

      @if($m->d || $m->d===0)
      <tr>
      <td class="td_option td_{{ str_replace(' ','',str_replace('-','',$m->qno))}}_4 option" data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" data-option="D" data-group="4">
        <input class='input {{ str_replace(' ','',str_replace('-','',$m->qno))}}_D {{ str_replace(' ','',str_replace('-','',$m->qno))}}_4' type="checkbox" name="{{ $m->qno}}[]"  data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" value="D"/> {!! $m->d !!}
      </td>
      </tr>
      @endif

      @if($m->e || $m->e===0)
      <tr>
      <td class="td_option td_{{ str_replace(' ','',str_replace('-','',$m->qno))}}_5 option" data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" data-option="E" data-group="5">
        <input class="input {{ str_replace(' ','',str_replace('-','',$m->qno))}}_E {{ str_replace(' ','',str_replace('-','',$m->qno))}}_5" type="checkbox" name="{{ $m->qno}}[]"  data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" value="E"/> {!! $m->e !!}
      </td>
      </tr>
      @endif

      @if($m->f || $m->f===0)
      <tr>
      <td class="td_option td_{{ str_replace(' ','',str_replace('-','',$m->qno))}}_6 option" data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" data-option="F" data-group="6">
        <input class="input {{ str_replace(' ','',str_replace('-','',$m->qno))}}_F {{ str_replace(' ','',str_replace('-','',$m->qno))}}_6" type="checkbox" name="{{ $m->qno}}[]"  data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" value="F"/> {!! $m->f !!}
      </td>
      </tr>
      @endif

      @if($m->g || $m->g==='0')
   <tr>
    <td class="td_option td_{{ str_replace(' ','',str_replace('-','',$m->qno))}}_7 option" data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" data-option="G" data-group="7">
        <input class='input {{ str_replace(' ','',str_replace('-','',$m->qno))}}_G {{ str_replace(' ','',str_replace('-','',$m->qno))}}_7' type="checkbox" name="{{ $m->qno}}[]"  data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" value="G"/> {!! $m->g !!}
    </td>
  </tr>
  @endif

  @if($m->h || $m->h==='0')
   <tr>
    <td class="td_option td_{{ str_replace(' ','',str_replace('-','',$m->qno))}}_8 option" data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" data-option="H" data-group="8">
        <input class='input {{ str_replace(' ','',str_replace('-','',$m->qno))}}_H {{ str_replace(' ','',str_replace('-','',$m->qno))}}_8' type="checkbox" name="{{ $m->qno}}[]"  data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" value="H"/> {!! $m->h !!}
    </td>
  </tr>
  @endif

  @if($m->i || $m->i==='0')
   <tr>
    <td class="td_option td_{{ str_replace(' ','',str_replace('-','',$m->qno))}}_9 option" data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" data-option="I" data-group="9">
        <input class='input {{ str_replace(' ','',str_replace('-','',$m->qno))}}_I {{ str_replace(' ','',str_replace('-','',$m->qno))}}_9' type="checkbox" name="{{ $m->qno}}[]"  data-id="{{ str_replace(' ','',str_replace('-','',$m->qno))}}" value="I"/> {!! $m->i !!}
    </td>
  </tr>
  @endif
 
</table>