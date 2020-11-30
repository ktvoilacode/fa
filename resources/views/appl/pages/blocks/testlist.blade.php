

@if(count($tests))
<div class="table-responsive">
<table class="table table-bordered">
  <thead>
    <tr class="bg-light">
      <th scope="col">#</th>
      <th scope="col">Test Name</th>
      
      <th scope="col">Action</th>
      <th scope="col">Status</th>
      <th scope="col">Valid Till</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($tests as $k=>$test)
	<tr>
      <th scope="row">{{$k+1}}</th>
      <td><a href="{{ route('test',$test->slug)}}">{{$test->name}}</a></td>
      
      
      <td>@if($status[$test->id]=='Active')
			<a href="{{ route('test.instructions',$test->slug) }}">
				<button class="btn  btn-sm btn-success">Try Now</button>
			</a>
			@else
  			@if($test->testtype->name == 'SPEAKING' || $test->testtype->name == 'WRITING')
  			<a href="{{ route('test.try',$test->slug)}}" class="btn btn-primary btn-sm  mb-1"><i class="fa fa-eye"></i> View Response</a>
        @elseif($status[$test->id]=='Expired')
         <a href="{{ route('test',$test->slug)}}" class="btn btn-sm btn-outline-success mb-1"> Buy Now</a>
  			@else
  			<a href="{{ route('test.analysis',$test->slug)}}" class="btn btn-sm btn-primary mb-1"><i class="fa fa-bar-chart "></i> Test Report</a>
  			@endif
			@endif
		</td>
		<td>
      	{{ $status[$test->id]}}
      </td>
      <td>{{ date('d M Y', strtotime($expiry[$test->id]))}}</td>
    </tr>		
	@endforeach
  </tbody>
</table>
</div>
@else
<div class="card">
	<div class="card-body">
		- No Tests -
	</div>
</div>
@endif
