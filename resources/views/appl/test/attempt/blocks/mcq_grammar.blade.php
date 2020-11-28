@foreach($test->mcq_order as $k=>$m)

@if($k!=0)<div class="mb-1 p-1"></div>@endif

@if(isset($m->extract))
  <div class="option rounded p-4 border mb-4">
    <h4 class="mb-3"><i class="fa fa-check-square-o"></i> {{ $m->extract->name }} </h4>
    {!!$m->extract->text !!}</div>
    <span class="d-none sentence_holder" data-qno="{{$m->qno}}"></span>
    <input type="hidden" name="{{$m->qno}}" value=""/>
@endif



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
  @include('appl.test.attempt.layouts.mcq_default')
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
  @include('appl.test.attempt.layouts.gre_numeric')
@endif

@if($m->layout == 'gre_fraction')
  @include('appl.test.attempt.layouts.gre_fraction')
@endif

</div>            
@endforeach