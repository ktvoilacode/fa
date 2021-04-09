
<div class="row question ">
  @if($f->label)
  <div class="col-12 " id="{{$f->qno}}">
    <div class="card-text f_label_{{$f->id}}"><b>{!! $f->label !!}</b>
    </div>
  </div>
  @endif
  <div class="col-12 col-md-1" id="{{$f->qno}}">
    <div class="card-text mb-3 f_qno_{{$f->id}}" ><span class="badge badge-warning h2">{{$f->qno}}</span>
    </div>
  </div>
  <div class="col-12 col-md-11">
    <div class="card-text"><div>
      @if($f->prefix ) <span class="f_prefix_{{$f->id}}">{!!$f->prefix !!}</span>  @endif 
      @if($answers) <span class="badge badge-primary">{{$f->answer}}</span> 
        @else
        @if($f->answer) <input type="text" class="fill input f_answer_{{$f->id}}" name="{{$f->qno}}" data-id="{{$f->qno}}" >
        @endif
      @endif
      @if($f->suffix )<span class="f_suffix_{{$f->id}}">{!!$f->suffix !!}</span>@endif
    </div>
  </div>
</div>
</div>