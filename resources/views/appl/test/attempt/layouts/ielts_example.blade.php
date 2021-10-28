<div class="p-3 mb-3 rounded  " style="border:1px solid #8bced6;background:#f9fdfd">
<h4><b>Example</b></h4>
<div class="row question">

  @if($f->label)
  <div class="col-12 col-md-4" id="{{$f->qno}}">
    <div class="card-text " ><b>{!! $f->label !!}</b></div>
  </div>
 @endif
  <div class="col-12 col-md">

      @if($f->prefix ) {!! $f->prefix !!}  @endif 

      @if($f->answer) 
      @if($f->layout=='two_blank' || $f->layout=='ielts_two_blank')
        <input type="text" class="fill input" value="{{ strtok($f->answer, '[') }}" @if(strlen($f->answer)>20) style="width:300px" @endif disabled >
        <?php echo get_string_between($f->answer,'[',']') ?>
        <input type="text" class="fill input" value="{{ substr($f->answer, strpos($f->answer, ']') + 1) }}" @if(strlen($f->answer)>20) style="width:300px" @endif disabled >
        
      @else
      <input type="text" class="fill input mb-0"  value="{{$f->answer}}" @if(strlen($f->answer)>20) style="width:300px" @endif disabled>
      @endif
      @endif
      @if($f->suffix ){!! $f->suffix !!}@endif
    
  </div>
</div>
</div>
