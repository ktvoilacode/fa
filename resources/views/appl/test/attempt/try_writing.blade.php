@extends('layouts.reading')
@section('title', 'Writing Test - '.$test->name)
@section('description', 'The Test page of '.$test->name)
@section('keywords', 'practice tests, '.$test->name)

@section('content')

@guest
<div class="alert alert-warning alert-dismissible alert-important fade show" role="alert">
  <strong>Note:</strong> Only registered users can submit the test and view the result. 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endguest

<style>
#split {
  height: 100vh;
}
#one,#two,#two,#four{background: white; }
#one,#two{height: 100vh;overflow:scroll;}
#three,#four{overflow:scroll;}
#one,#three{background: #fffadd;;}
.gutter {
    background-color: #eee;
    background-repeat: no-repeat;
    background-position: 50%;

}
.gutter.gutter-horizontal {
    background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAeCAYAAADkftS9AAAAIklEQVQoU2M4c+bMfxAGAgYYmwGrIIiDjrELjpo5aiZeMwF+yNnOs5KSvgAAAABJRU5ErkJggg==');
    cursor: col-resize;
}
.gutter.gutter-vertical {
    background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAFAQMAAABo7865AAAABlBMVEVHcEzMzMzyAv2sAAAAAXRSTlMAQObYZgAAABBJREFUeF5jOAMEEAIEEFwAn3kMwcB6I2AAAAAASUVORK5CYII=');
    cursor: row-resize;
}
#flex {
  display: flex;
}
.vbox{
  height: 80vh;
}
</style>


<div class="" style="padding-left:0px;padding-right:0px;">

<div class="bg-white rounded ">
    <div class="row no-gutters">
        @if(!$attempt)
        <div class="col-12 d-none d-md-block">
          <form method="post" action="{{ route('attempt.upload',$test->slug) }}" enctype="multipart/form-data" id="write">
          <div id="flex" >
            <div id="one"> 
              <div>
                @if(!$attempt)
                <div class=" ">
                    <div class=" p-4  rounded mb-4 mb-md-0" style="background: #fffadd;">
                      <div class="row">
                        <div class="col-12 col-md-12">
                          <img src="{{  url('/').'/images/general/writing.png'}}" class="  mb-4 mx-auto d-block" style="max-width:100px;"/>
                        </div>
                        <div class="col-12 col-md-12">
                          @if(strlen(trim(strip_tags($test->description)))>0)
                          <div class="writing">{!!$test->description!!}</div>
                          @else
                          <h5>Enter your question</h5>
                          <textarea class="form-control " name="question" rows=4></textarea>
                          @endif
                        </div>

                      </div>
                    
                    </div>
                </div>
                @endif
                
              </div>
            </div>
            <div id="two"> 
              <div class="p-4 row mt-3" >
                <div class="col-12 ">
                 @if(!$attempt)
                      @include('appl.test.attempt.blocks.write2')
                 @else
                      @include('appl.test.attempt.blocks.writing_file')
                 @endif
                </div>
               </div>
            </div>
          </div>
        </form>
        </div>
        
        <div class="col-12 vbox d-md-none d-block">
          <form method="post" action="{{ route('attempt.upload',$test->slug) }}" enctype="multipart/form-data" id="write">
          <div id="split">
            <div id="three" style="width:100%" > 
        @if(!$attempt)
        <div class=" ">
            <div class=" p-4  rounded mb-4 mb-md-0" style="background: #fffadd;">
              <div class="row">
                <div class="col-12 col-md-12">
                  <img src="{{  url('/').'/images/general/writing.png'}}" class="  mb-4 mx-auto d-block" style="max-width:100px;"/>
                </div>
                <div class="col-12 col-md-12">
                  @if(strlen(trim(strip_tags($test->description)))>0)
                  <div class="writing">{!!$test->description!!}</div>
                  @else
                  <h5>Enter your question</h5>
                  <textarea class="form-control " name="question" rows=4></textarea>
                  @endif
                </div>

              </div>
            
            </div>
        </div>
        @endif
            </div>
            <div id="four" style="width:100% "> 
              <div class="p-4 row " >
                <div class="col-12 mt-3">
                  <div class="mb-3"><span class="text-danger">*The above and below containers are scrollable</span></div>
               @if(!$attempt)
                      @include('appl.test.attempt.blocks.write')
                 @else
                      @include('appl.test.attempt.blocks.writing_file')
                 @endif
               </div>
             </div>
            </div>
          </div>
          </form>
        </div>
        
        <div class="col-12 ">
          
      </div>

      @else

      <div  style="margin: 5px auto;max-width: 900px;">
        @include('appl.test.attempt.blocks.writing_file')

        <div class="py-4">
        <a href="/home" class="ml-4 mb-4">back to dashbaord</a>
      </div>
      </div>

      @endif
        
    </div>
    </div>
</div>


@endsection
