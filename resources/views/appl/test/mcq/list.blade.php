
 @if($objs->total()!=0)

              @foreach($objs as $key=>$obj)  

                            <div class="p-4 rounded bg-white border-left mb-3">
  <div class="row">
    <div class="col-2 col-md-1">
      <a href=" {{ route($app->module.'.show',[$app->test->id,$obj->id]) }} " style="font-size:30px;">{{ $obj->qno }}</a></div>
    <div class="col-10 col-md-11">
      <div>
        <span class="float-right"><a href=" {{ route($app->module.'.show',[$app->test->id,$obj->id]) }} ">{{ ($obj->created_at) ? $obj->created_at->diffForHumans() : '' }}</a></span>
            <h5><a href=" {{ route($app->module.'.show',[$app->test->id,$obj->id]) }} ">
                  {!! $obj->question !!}
                  </a></h5>
                  @if(isset($obj->section))
                  <b>Section: {{$obj->section->name}}</b>

                  @else
                  <b class="text-danger">Section: -</b>

                  @endif
                  </div>
                  
                   
                  
    </div>
    
  </div>
</div>  

             
              @endforeach      

        @else
        <div class="card card-body bg-light">
          No {{ $app->module }} found
        </div>
        @endif
        <nav aria-label="Page navigation  " class="card-nav @if($objs->total() > config('global.no_of_records'))mt-3 @endif">
        {{$objs->appends(request()->except(['page','search']))->links()  }}
      </nav>
