@foreach($extract->mcq_order as $k=>$m)
@if($k!=0)<hr>@endif
<div class="mb-3">
    <div class="row">
        <div class="col-4 col-md-3 col-lg-2">
          <div id="{{$m->qno}}" class="qno">{{$m->qno}}</div>
      </div>
      <div class="col-12 col-md-9 col-lg-10">
          <div class="question">{!! $m->question !!}</div>
      </div>
  </div>

@if(!$m->layout || $m->layout=='no_instruction')
  @include('appl.test.attempt.layouts.mcq_ielts')
@endif

@if($m->layout == 'gre1')
  @include('appl.test.attempt.layouts.gre1')
@endif

@if($m->layout == 'gre2')
  @include('appl.test.attempt.layouts.gre2')
@endif


@if($m->layout == 'gre3')
  @include('appl.test.attempt.layouts.gre3')
@endif

@if($m->layout == 'gre_maq')
  @include('appl.test.attempt.layouts.gre_maq')
@endif

@if($m->layout == 'gre_numeric')
<h5><b> Enter your answer</b></h5>
  <input class='input_ {{$m->qno}} form-control w-25' type="text" name="{{$m->qno}}"  data-id="{{$m->qno}}" value=""/>
@endif

</div>            
@endforeach