
<div class="table-responsive pt-3">
<table class="table table-bordered mb-0">
  <thead>
    <tr>
      <th scope="col" style="width:10%">Qno</th>
      <th scope="col" style="width:20%">Question</th>
      <th scope="col" style="width:20%">Your Response</th>
      
      <th scope="col" style="width:10%">Result</th>
      @if(\auth::user())
        @if(\auth::user()->isAdmin())
        @if(isset($score_params))
        @if($test->testtype->name=='DUOLINGO' )
      <th scope="col" style="width:10%">Score</th>
      <th scope="col" style="width:30%">Evaluate</th>
      @endif
      @endif
        @endif
      @endif
    </tr>
  </thead>
  <tbody>
    @foreach($result as $qno => $item)
    @if(isset($item->qno))
    <tr>
      <th scope="row">{{ $item->qno}}</th>
      <td class="text-wrap text-break">
        @if($item->fillup)
          @if($item->fillup->label)<b class=''>{{$item->fillup->label}}</b> @endif
          <div>
          @if($item->fillup->prefix)<span>{!! $item->fillup->prefix !!}</span> @endif
          @if($item->fillup->answer)<span class="text-success "><u>{{$item->fillup->answer}}</u></span> @endif
          @if($item->fillup->suffix)<span>{{$item->fillup->suffix}}</span> @endif
          </div>
        @else

          @if(isset($item['mcq']->question))
            @if($item['mcq']->question)<b class='h6' style="line-height: 1.5">{!! $item['mcq']->question !!}</b> @endif
          @elseif( $test->testtype->name=='WRITING')
           {!! $test->description !!}
          @endif
          <div>

           @if(isset($item['mcq']->layout))
          @if($item['mcq']->layout!='gre_numeric' && $item['mcq']->layout!='gre_fraction' && $item['mcq']->layout!='gre_sentence')
          @foreach(['a','b','c','d','e','f','g','h','i'] as $opt)
            @if(isset($item['mcq']->$opt))
            @if($item['mcq']->$opt || $item['mcq']->$opt==='0' )<div class="@if(strpos($item['mcq']->answer, strtoupper($opt)) !== FALSE) text-success @endif  p-1 mb-1 rounded" >({{strtoupper($opt)}}){!!$item['mcq']->$opt!!} </div> @endif
            @endif

          @endforeach
          @elseif($item['mcq']->layout=='gre_numeric')
          <div class="p-1">Answer: &nbsp;<b>{{$item['mcq']['a']}}</b></div>
          @elseif($item['mcq']->layout=='gre_fraction')
          <div class="p-1">Answer: &nbsp;<b>{{$item['mcq']['a']}}/{{$item['mcq']['b']}}</b></div>
          @endif



          @if($item['mcq']->explanation)
          <div class="bg-light rounded p-3 mt-3">
          <div><b>Explanation</b></div>
          <div>{!! $item['mcq']->explanation !!}</div>
        </div>
          @endif

           @endif

         
          </div>


        @endif
      </td>
      <td class="text-wrap text-break">
        <div style="max-width: 200px">

        @if($item->response) {!! $item->response !!} 
        @else
        @if(isset($item->fillup->id))
          @if(\Storage::disk('s3')->exists('responses/'.$test->id.'/'.$userid.'_'.$item->fillup->id.'.wav'))
          <audio controls>
              <source src="{{ \Storage::disk('s3')->url('responses/'.$test->id.'/'.$userid.'_'.$item->fillup->id.'.wav')}}" type="audio/ogg">
            Your browser does not support the audio element.
            </audio>
          @endif
        @endif
        @endif

        
      </div>
      </td>

      
      <td>
      @if($item->status)
        @if($item->accuracy==1) 
          <span class="text-success"><i class="fa fa-check-circle"></i></span>
        @elseif($item->accuracy==2) 
         <span class="text-danger"><i class="fa fa-times-circle"></i></span> 
        @else 
          <span class="text-danger"><i class="fa fa-times-circle"></i></span>
        @endif
      @else
          <span class="text-info"><i class="fa fa-circle-o"></i> Under Review</span>

      @endif
      </td>

        
      
        @if(\auth::user())
        @if(\auth::user()->isAdmin())
        @if(isset($score_params))
         @if($test->testtype->name=='DUOLINGO')
         <td>
        {{ $item->score }}
      </td>
         <td>
         @if($item['fillup']['layout'])
         @if(isset($score_params[$item['fillup']['layout']]))
          @foreach($score_params[$item['fillup']['layout']] as $param)
            @include('appl.test.attempt.blocks.evaluate')
          @endforeach
        @endif
          @endif
        </td>
          
          @endif
          @endif
        @endif
      @endif
      

    </tr>
    @elseif(isset($item['qno']))
    
    <tr>
      <td scope="row">{{ $item['qno']}}</td>
      <td class="text-wrap text-break">
        @if(isset($item['fillup']))
          @if($item['fillup']->label)<b class=''>{{$item['fillup']->label}}</b> @endif
          <div>
          @if($item['fillup']->prefix)<span>{!! $item['fillup']->prefix !!}</span> @endif
          @if($item['fillup']->answer)<span class="text-success "><u>{{$item['fillup']->answer}}</u></span> @endif
          @if($item['fillup']->suffix)<span>{{$item['fillup']->suffix}}</span> @endif
          </div>
        @elseif(isset($item['mcq']))

          @if($item['mcq']->question)<b class='h6' style="line-height: 1.5">{!! $item['mcq']->question !!}</b> @endif
          <div>
          @if($item['mcq']->a || $item['mcq']->a==='0')<div class="@if(strpos($item['mcq']->answer, 'A') !== FALSE) text-success @endif">(A){{$item['mcq']->a}}</div> @endif
          @if($item['mcq']->b || $item['mcq']->b==='0')<div class="@if(strpos($item['mcq']->answer, 'B') !== FALSE) text-success @endif">(B){{$item['mcq']->b}}</div> @endif
          @if($item['mcq']->c || $item['mcq']->c==='0')<div class="@if(strpos($item['mcq']->answer, 'C') !== FALSE) text-success @endif">(C){{$item['mcq']->c}}</div> @endif
          @if($item['mcq']->d || $item['mcq']->d==='0')<div class="@if(strpos($item['mcq']->answer, 'D') !== FALSE) text-success @endif">(D){{$item['mcq']->d}}</div> @endif
          @if($item['mcq']->e || $item['mcq']->e==='0')<div class="@if(strpos($item['mcq']->answer, 'E') !== FALSE) text-success @endif">(E){{$item['mcq']->e}}</div> @endif
          @if($item['mcq']->f || $item['mcq']->f==='0')<div class="@if(strpos($item['mcq']->answer, 'F') !== FALSE) text-success @endif">(F){{$item['mcq']->f}}</div> @endif
          @if($item['mcq']->g || $item['mcq']->g==='0')<div class="@if(strpos($item['mcq']->answer, 'G') !== FALSE) text-success @endif">(G){{$item['mcq']->g}}</div> @endif
          @if($item['mcq']->h || $item['mcq']->h==='0')<div class="@if(strpos($item['mcq']->answer, 'H') !== FALSE) text-success @endif">(H){{$item['mcq']->h}}</div> @endif
          @if($item['mcq']->i || $item['mcq']->i==='0')<div class="@if(strpos($item['mcq']->answer, 'I') !== FALSE) text-success  @endif">(I){{$item['mcq']->i}}</div> @endif
          </div>


        @endif
      </td>
      <td>
      @if($item['response']) {!! $item['response'] !!} 
        @else
          @if(isset($item['fillup']->id))
          @if(\Storage::disk('s3')->exists('responses/'.$test->id.'/'.$userid.'_'.$item['fillup']->id.'.wav'))
            <audio controls>
              <source src="{{ \Storage::disk('s3')->url('responses/'.$test->id.'/'.$userid.'_'.$item['fillup']->id.'.wav')}}" type="audio/ogg">
            Your browser does not support the audio element.
            </audio>
          @endif
          @endif
        @endif</td>
        
      <td>
        @if($item['status'])
          @if($item['accuracy']==1 || $item['accuracy']>1 ) 
          <span class="text-success"><i class="fa fa-check-circle"></i></span>
          @else 
            <span class="text-danger"><i class="fa fa-times-circle"></i></span>
          @endif
        @else
            <span class="text-info"><i class="fa fa-circle-o"></i> Under Review</span>
        @endif
      </td>
      


      @if(\auth::user())
        @if(\auth::user()->isAdmin())
        @if(isset($score_params))
         @if($test->testtype->name=='DUOLINGO')
         <td>
          @if(isset($item['score']))
        {{ $item['score'] }}
        @endif
      </td>
         <td>
          @if(isset($score_params[$item['fillup']['layout']]))
          @foreach($score_params[$item['fillup']['layout']] as $param)
            @include('appl.test.attempt.blocks.evaluate')
          @endforeach
          @endif
          </td>
          @endif
          @endif
        @endif
      @endif
    


    </tr>
    @endif
    @endforeach
  </tbody>
</table>

@if(($test->testtype->name=='DUOLINGO' || $test->testtype->name=='WRITING') && !request()->get('open') && !request()->get('source'))
<div class="form-group mb-1 mt-4">
  <label for="exampleTextarea">Score (optional)</label>
    <input class="form-control" name="direct_score" value="{{ $score }}" />
    <input class="form-control" type="hidden" name="mock"  value="{{request()->get('mock')}}"/>
     
   </div>
    <label for="exampleTextarea">Comments </label>
<textarea class="form-control summernote" id="exampleTextarea" name="comments" rows="3">@if(!is_array($result)) @if($result->where('comment','!=',NULL)->first()) {!! $result->where('comment','!=',NULL)->first()->comment !!}@endif @endif</textarea>
   </div>
   @else

      @if(!is_array($result)) 
        @if(strip_tags($result->where('comment','!=',NULL)->first())) 
      <div class="bg-light border my-3 p-3 rounded">
        <h3 class="my-2">Comments</h3>
        <div>{!! $result->where('comment','!=',NULL)->first()->comment !!}  </div>
        @if(Storage::disk('public')->exists('feedback/feedback_'.$result->first()->id.'.pdf'))
              <a href="{{route($app->module.'.download',[$result->first()->id])}}?pdf=1" >
                <button type="button" class="btn btn-sm btn-outline-success float-left mr-2">Detailed Feedback</button>
              </a>
        @endif
      </div>
        @endif 
      @endif

   @endif
 </div>


</div>