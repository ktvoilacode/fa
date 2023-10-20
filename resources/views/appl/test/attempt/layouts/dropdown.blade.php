
<div class="row question ">
  @if($test->category->name!='PTE')
  <div class="col-12 col-md-1" id="{{$f->qno}}">
    <div class="card-text mb-3" ><span class="badge badge-warning h2">{{$f->qno}}</span>
    </div>
  </div>
  @endif
  <div class="col-12 col-md">
    <div class="card-text">
    <span class="question " id="{{$f->qno}}">
      @if($f->prefix ) {!! $f->prefix !!}  @endif 
      @if($f->answer)
      &nbsp;
      <span style="display:inline-block;">
        <select class=" input fill" name="{{$f->qno}}" data-id="{{$f->qno}}">
        <option value=""></option>
        @foreach(explode('/',$f->label) as $option)
        <option value="{{ trim($option) }}">{{$option}}</option>
        @endforeach   
        </select>
      </span>
      @if($answers) 
          @if(isset($result))
                @if($result)
            <input type="text" class="fill input" name="{{$f->qno}}[]" data-id="{{$f->qno}}" value="{{$result[$f->qno]['response']}}">
                  @if($result[$f->qno]['accuracy'])
                    <span class="text-success"><i class="fa fa-check-circle"></i> </span>
                  @else
                    <span class="text-danger"><i class="fa fa-times-circle"></i> </span>
                  @endif
                @endif
            @else
        <span class="badge badge-primary">{{$f->answer}}</span> 
        @endif
      @endif
      &nbsp; 
      @endif
      @if($f->suffix ){!! $f->suffix !!} @endif
    </span>

    </div>
  </div>
</div>