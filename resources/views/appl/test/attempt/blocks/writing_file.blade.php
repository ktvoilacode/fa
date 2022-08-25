

<div class="rounded p-4 mb-4" style="">
	@if($attempt->answer)
	<div class="alert alert-success alert-important mb-4 border border-success">
	<h5>Your evaluation is ready!  </h5>
	<a href="{{ route('test.review',$test->slug)}}?product=@if($product){{$product->slug}}@endif">
	<button type="button" class="btn btn-bg btn-success">View Expert Review</button>
	</a>
	</div>
	@else
	<div class="alert alert-success alert-important mb-4 border border-success" role="alert">
  	<h5 class="mb-0">Your writing task has been submitted. Please check back later for the evaluation. Good Luck! </h5>
	</div>
	
	@endif
	

	@if(!\auth::user()->check_order(\auth::user()->id,$test->id) && !$attempt->answer)
	<div class="alert alert-warning alert-important mb-4 " role="alert">
  	<h4 class="mb-0">Do you want a quick evaluation? </h4>
  	<p> Try our premium evaluation to get the scores within 24 hours</p> 
  	<a href="{{ route('product.checkout','writing-evaluation')}}?details=Quick Evaluation of test - {{$test->name}} for {{\auth::user()->name}} ({{\auth::user()->id}})&test_id={{$test->id}}" class="btn btn-success"><strike>Rs.250</strike> &nbsp;Rs.150</a>&nbsp;&nbsp; 25% OFF (Limited period offer)

  	<a href="{{ route('myorders') }}" class="float-right">My Transactions</a>
	</div>
	@elseif(\auth::user()->check_order(\auth::user()->id,$test->id)) 
	<div class="alert alert-warning alert-important mb-4 " role="alert">
		Premium evaluation is enabled!
	</div>
	@endif

	<h4 class="mb-3 text-primary">Your Response </h4>
	<div class="bg-light border rounded p-3 mb-3">
	{!! $attempt->response !!}
	</div>
	
	
	
</div>