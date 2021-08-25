

@if($test->fillup_order[0]->layout=='duolingo_missing_letter')
<style>
  input.duo,input.lightb{width:30px;height:30px;text-align: center;border:1px solid #c9dbe4;margin: 0px;padding:0px;float: left; font-size: 18px;margin-top: 0px;font-weight: 800}
  .lightb{background: #e7f2f9;color:#52b6e8;}
</style>
<div class="card-text mb-5 mb-md-0" style="overflow: auto;">
@endif
@foreach($test->fillup_order as $f)

    @if(isset($f->extract))
  <div class="option rounded p-4 border mb-4">
    <h4 class="mb-3"><i class="fa fa-check-square-o"></i> {{ $f->extract->name }} </h4>
    {!!$f->extract->text !!}</div>
    <span class="d-none sentence_holder" data-qno="{{$f->qno}}"></span>
    <input type="hidden" name="{{$f->qno}}" value=""/>
@endif


    @if($f->qno==-1)
      @include('appl.test.attempt.layouts.fillup_example') 
    @else
      @if($f->layout=='gre_sentence')
        @include('appl.test.attempt.layouts.gre_sentence') 
      @elseif($f->layout=='dropdown')
        @include('appl.test.attempt.layouts.dropdown') 
      @elseif($f->layout=='paragraph')
        @include('appl.test.attempt.layouts.ielts_paragraph') 
      @elseif($f->layout=='duolingo_missing_letter')

        @include('appl.test.attempt.layouts.duolingo_missing_letter') 
      @elseif($f->layout=='cloze_test')
        @include('appl.test.attempt.layouts.cloze_test') 
      @elseif($f->layout=='ielts_two_blank')
      <div class=" question">
        <div class="card-text">
        @include('appl.test.attempt.layouts.ielts_two_blank') 
      </div>
      </div>
      @elseif($f->layout=='two_blank')
        @include('appl.test.attempt.layouts.two_blank') 
      @elseif($f->layout=='ielts_label')
        @include('appl.test.attempt.layouts.ielts_label') 
      @elseif($f->layout=='ielts_number')
        @include('appl.test.attempt.layouts.ielts_title')  
      @elseif($f->layout=='listen_audio_options')
        @include('appl.test.attempt.layouts.listen_audio_options')   
      @else
        @include('appl.test.attempt.layouts.gre_blank') 
      @endif   
    @endif
    @endforeach

@if($test->fillup_order[0]->layout=='duolingo_missing_letter')
</div>
@endif
 

