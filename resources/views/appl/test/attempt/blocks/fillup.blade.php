@if($test->category->name=='OET') 
  @if($test->testtype->name=='READING')
    @foreach($extract->fillup_order as $f)
      @if($f->qno==-1 && !$f->label)
          @include('appl.test.attempt.layouts.oet_reading_example') 
      @elseif($f->label)
          @include('appl.test.attempt.layouts.oet_reading_label') 
      @else
            @include('appl.test.attempt.layouts.oet_reading_qno')
      @endif
    @endforeach
  @else
    @foreach($extract->fillup_order as $f)
        @include('appl.test.attempt.layouts.oet_listening_label')    
    @endforeach
  @endif
@elseif($test->category->name=='IELTS' || $test->category->name=='GENERAL')
  @if($extract->layout == 'default' ||  !$extract->layout)
    @foreach($extract->fillup_order as $f)
      @if($f->layout=='ielts_two_blank')
        @include('appl.test.attempt.layouts.ielts_two_blank') 
      @elseif($f->layout=='two_blank')
        @include('appl.test.attempt.layouts.two_blank') 
      @elseif($test->category->name=='IELTS' && $f->qno==-1)
          @include('appl.test.attempt.layouts.ielts_example')
      @elseif($f->layout=='ielts_number') 
            @include('appl.test.attempt.layouts.ielts_title') 
      @elseif($f->layout) 
            @include('appl.test.attempt.layouts.'.$f->layout) 
      @else
          @include('appl.test.attempt.layouts.ielts_label') 
      @endif   
    @endforeach
  @else
    @foreach($extract->fillup_order as $f)
      @if($test->category->name=='IELTS' && $f->qno==-1)
          @include('appl.test.attempt.layouts.ielts_example') 
      @elseif($f->layout=='two_blank')
        @include('appl.test.attempt.layouts.two_blank') 
      @elseif($f->layout=='ielts_two_blank')
        @include('appl.test.attempt.layouts.ielts_two_blank') 
      @else

          @if($f->layout=='ielts_number') 
            @include('appl.test.attempt.layouts.ielts_title') 
          @elseif($f->layout) 
            @include('appl.test.attempt.layouts.'.$f->layout) 
          @else
            @include('appl.test.attempt.layouts.'.$extract->layout) 
          @endif
      @endif 
    @endforeach
  @endif

@endif

