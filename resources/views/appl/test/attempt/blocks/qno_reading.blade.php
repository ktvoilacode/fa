<div class="bottom-qno">

<div class="bottom-wrap">
    <div class="pallete qshow mb-0 h5 pb-0">
    	<div class="row no-gutters">
    		<div class="col-12 col-md col-lg">
                <div class="section rounded mr-md-1 mb-2 mb-md-0">
                <div class="row">
                    <div class="col-6 col-md-4">
                        <span class="pallete-control"><i class="fa fa-th"></i> Questions  <span class="angle"><i class="fa fa-angle-double-down"></i></span></span>
                    </div>

                    <div class="col-2 col-md-4">

                        @if(!$answers)
                       <span class="badge badge-warning float-right " id="timer">00:00</span>
                        <span class="badge badge-warning float-center d-none" id="timer2">00:00</span>
                        @endif

                    </div>

                    <div class="col-4 col-md-4">

                        @if(!$answers)
                        <div class="mr-2">
                        @if(!isset($view))
                 <button type="button" class="btn btn-warning btn-sm ml-2 btn-block mr-2" type="button" data-toggle="modal" data-target="#exampleModal">Submit</button>
                 @else
                 <button type="button" class="btn btn-warning btn-sm ml-2 btn-block mr-2" type="button" data-toggle="modal" data-target="#exampleModal">Submit</button>
                 @endif
                    </div>
                    @endif

                    </div>

                    
                </div>

                </div>
    			
    		</div>
    		
    		<div class="col-12 col-md-6 col-lg-4 @if(count($test->sections)<2) d-none @endif">
    			<div class="section section2 rounded ml-md-1 ">
    				<span class="section-name mt-1"> SECTION <span class="section-number">1</span></span>
                   
    				<span class="float-right">
    				<button type="button" class="button btn  btn-sm prev" style="display: none" data-id="0"><i class="fa fa-angle-left"></i> PREV
                    </button>
    					<button  type="button"class="button btn btn-sm next" data-id="2">NEXT <i class="fa fa-angle-right"></i></button>
                    </span>
    				
    			
    			
    		
    		</div>
    		</div>
    	</div>
    	

    
     </div>
    <div class="mt-2 bottom-scroll {{$i=1}}"> 
        @foreach($test->sections as $se=>$section)
            @foreach($section->extracts as $extract)
                @foreach($extract->mcq_order as $m)
                @if($m->qno!=-1)
                <div class="bottom-box  text-center">
                    <div class="sno bottom-sno  s{{$m->qno}}" data-id="{{$m->qno}}" data-section="{{$se}}">{{$m->qno}}</div>
                </div>
                @endif
                @endforeach
                @foreach($extract->fillup_order as $w=>$m)
                @if($m->qno!=-1 && $m->qno)
                <div class="bottom-box  text-center">
                <div class="sno bottom-sno  s{{$m->qno}}" data-id="{{$m->qno}}" data-section="{{$se}}">{{$m->qno}}</div>
                </div>
                @endif
                
                @endforeach
                
            @endforeach  

        @endforeach
        
    </div>

    </div>
</div>