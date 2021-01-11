
<div class="sticky-top d-none d-sm-block ml-4">
<div class=" p-4  sidebar ">
<h5 class="mb-4 "><i class="fa fa-th"></i> Questions  &nbsp;
	@if(!$answers)
	@if($app->test->test_time)
	<span class="badge badge-warning float-right" id="timer2">{{$app->test->test_time}}:00 </span>
	@endif
	@endif
</h5>
<div class="row no-gutters">
@for($i=1;$i <= $qcount; $i++ ) 
<div class="col-2 col-md-3 col-lg-2">
<div class="box pr-2 pb-2 text-center">
<div class="sno s{{$i}}" data-id="{{$i}}">{{$i}}</div>
</div>
</div>
@endfor
</div>
<div class="mt-3">
	<span class="answered-spot"><i class="fa fa-circle "></i> </span>Answered &nbsp;<span class="unanswered"> <i class="fa fa-circle "></i> </span>Unanswered
	</div>
	@if(!$answers)
	<button class="btn btn-warning  mt-4" type="button" data-toggle="modal" data-target="#exampleModal">Submit Test</button>
	<button class="btn btn-outline-light mt-4" type="button" data-toggle="modal" data-target="#exampleModal2">Report Error</button>
	@endif
</div>
</div>

<div class="d-block d-sm-none " style="position:fixed;bottom:0;left:0;right:0;z-index:10">
<div class=" p-4  sidebar ">
<h5 class="mb-0 "><span class="qshow"><i class="fa fa-th"></i> Questions <span class="angle"><i class="fa fa-angle-double-up"></i></span></span>  
	@if(!$answers)
	@if($app->test->test_time)
	<span class="badge badge-warning" id="timer">{{$app->test->test_time}}:00</span>
	@endif
	@endif

@if(!$answers)
<button class="btn btn-outline-light  btn-sm float-right ml-2" type="button" data-toggle="modal" data-target="#exampleModal2">Rep</button>
<button class="btn btn-warning btn-sm float-right" type="button" data-toggle="modal" data-target="#exampleModal">Submit</button>
@endif

</h5>
<div class="qdata mt-4">
<div class="row no-gutters" style="max-height:150px;overflow: scroll">
@for($i=1;$i <= $qcount; $i++ ) 
<div class="col-2">
<div class="box pr-2 pb-2 text-center">
<div class="sno s{{$i}}" data-id="{{$i}}">{{$i}}</div>
</div>
</div>
@endfor
</div>


</div>
</div>
</div>