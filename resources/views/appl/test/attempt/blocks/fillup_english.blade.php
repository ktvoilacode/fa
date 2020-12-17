


@if($section->fillup_order[0]->layout=='duolingo_missing_letter')
<div class="pt-3 pb-5 text-center duo-heading"><b>Type the missing letters to complete the text below</b></div>
<div class="card-text mb-5 mb-md-0" style="overflow: auto;">
@endif

@foreach($section->fillup_order as $fc=>$f)
  

  @if(isset($f->extract))
    <div class="option rounded p-4 border mb-4" >
    <h4 class="mb-3"><i class="fa fa-check-square-o"></i> {{ $f->extract->name }} </h4>
    {!!$f->extract->text !!}
    </div>
    <span class="d-none sentence_holder" data-qno="{{$f->qno}}"></span>
    <input type="hidden" name="{{$f->qno}}" value=""/>
  @endif

  <div class="wrap qwrap_{{$f->qno}}" data-layout="{{$f->layout}}" data-id="{{$f->id}}"  @if(isset($testedit) && request()->get('live_edit')) contenteditable="true"  @endif>
    @if($f->qno==-1)
      @include('appl.test.attempt.layouts.fillup_example') 
    @else
      @if($f->layout=='gre_sentence')
        @include('appl.test.attempt.layouts.gre_sentence') 
      @elseif($f->layout=='dropdown')
        @include('appl.test.attempt.layouts.dropdown') 
      @elseif($f->layout=='paragraph')
        @include('appl.test.attempt.layouts.ielts_paragraph') 
      @elseif($f->layout=='cloze_test')
        @include('appl.test.attempt.layouts.cloze_test') 
      @elseif($f->layout=='ielts_two_blank')
        @include('appl.test.attempt.layouts.ielts_two_blank') 
      @elseif($f->layout=='duolingo_missing_letter')
        @include('appl.test.attempt.layouts.duolingo_missing_letter') 
      @elseif($f->layout=='two_blank')
        @include('appl.test.attempt.layouts.two_blank')  
      @elseif($f->layout=='ielts_label')
        @include('appl.test.attempt.layouts.ielts_label')
      @elseif($f->layout=='ielts_number')
        @include('appl.test.attempt.layouts.ielts_title')   
      @elseif($f->layout=='listen_audio_options')
        @include('appl.test.attempt.layouts.listen_audio_options') 
      @elseif($f->layout=='listen_audio_question')
        @include('appl.test.attempt.layouts.listen_audio_question')   
      @elseif($f->layout=='readaloud')
        @include('appl.test.attempt.layouts.readaloud') 
      @elseif($f->layout=='speak')
        @include('appl.test.attempt.layouts.speak')  
      @elseif($f->layout=='write')
        @include('appl.test.attempt.layouts.write')  
      @elseif($f->layout=='select_words')
        @include('appl.test.attempt.layouts.select_words')  
      @else
        @include('appl.test.attempt.layouts.gre_blank') 
      @endif   
    @endif

    @if(isset($testedit) && request()->get('live_edit'))
    <div class="save_button" style="right:-140px"><button class="btn btn-fillup-save btn-outline-secondary" type="button" data-token="{{ csrf_token() }}" data-id="{{$f->id}}" data-url="{{route('fillup.ajaxupdate',[$test->id,$f->id])}}">Save Q{{$f->qno}}</button></div>
  @endif
    </div>
    @endforeach
  
@if($section->fillup_order[0]->layout=='duolingo_missing_letter')
</div>
@endif
