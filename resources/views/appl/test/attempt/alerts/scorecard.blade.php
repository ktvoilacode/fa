<div class="col-12 col-md-6">
             <div class="text-center  mt-3 mb-3 mt-md-0 mb-md-0 float-md-right border bg-light p-3 rounded ">
              
              @if($test->test_id==9)
                <div class="">Score </div>
                @if(!request()->get('session_id'))
                  @if(is_numeric($score))
                    <div class="display-4">{{ $user->duolingoRange($score) }}</div>
                  @else
                   <div class="h3 text-primary">{{ $user->duolingoRange($score) }}</div>
                  @endif
                @else
                <div class="display-4">{{$score}}</div>
                @endif
              
               @elseif($test->test_id==3)
                <div class="">Score </div>
                
                <div class="display-4">{{$score}}</div>
                
                
    
              @else
                
                  <div class="">Score </div>

                  @if(!$review)
                  <div class="display-4">{{ $score }} 
                    @if($test->marks) / {{ $test->marks}} @endif </div>
                  @else
                  <div class="h5 badge badge-warning mt-3">Under Review</div>
                  @endif
               
              @endif
            </div>
            @if($band)
            <div class="text-center  mt-3 mb-3 mt-md-0 mb-md-0 float-md-right border bg-light p-3 rounded mr-0 mr-md-4">
              <div class="">&nbsp;&nbsp;&nbsp; Band &nbsp;&nbsp;&nbsp;</div>
              <div class="display-4">{{ $band }} </div>
            </div>
            @elseif($points)
            @if($test->test_id!=9)
            <div class="text-center  mt-3 mb-3 mt-md-0 mb-md-0 float-md-right border bg-light p-3 rounded mr-0 mr-md-4">
              <div class="">&nbsp;&nbsp;&nbsp; Points &nbsp;&nbsp;&nbsp;</div>
              <div class="display-4">{{ $points }} </div>
            </div>
            @endif
            @endif
          </div>