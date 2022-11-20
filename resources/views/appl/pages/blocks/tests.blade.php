

<div class="bg-white p-3">
	<div>
		
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="myproduct-tab" data-toggle="tab" href="#myproduct" role="tab" aria-controls="myproduct" aria-selected="false"><h4 class="mt-2">My Products</h4></a>
			</li>
			<li class="nav-item">
				<a class="nav-link " id="mytest-tab" data-toggle="tab" href="#mytest" role="tab" aria-controls="mytest" aria-selected="false"><h4 class="mt-2">My Tests</h4></a>
			</li>
			
			
		</ul>
	</div>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade " id="mytest" role="tabpanel" aria-labelledby="mytest-tab">
  	<div class="mt-4">
  	<form class=" mb-4" method="GET" action="{{ route('home') }}">
			<div class="input-group input-group-lg">
				<div class="input-group-prepend">
					<div class="input-group-text"><i class="fa fa-search"></i></div>
				</div>
				<input class="form-control " id="search" name="item" autocomplete="off" type="search" placeholder="Search" aria-label="Search" 
				value="{{Request::get('item')?Request::get('item'):'' }}">
			</div>
		</form>
    <div id="search-items" class="">
		@include('appl.pages.blocks.testlist')
	</div>
	</div>
  </div>
  <div class="tab-pane fade show active" id="myproduct" role="tabpanel" aria-labelledby="myproduct-tab">
  	
  	<div class="mt-4">
  		<form class=" mb-4" method="GET" action="{{ route('home') }}">
			<div class="input-group input-group-lg">
				<div class="input-group-prepend">
					<div class="input-group-text"><i class="fa fa-search"></i></div>
				</div>
				<input class="form-control " id="search2" name="item2" autocomplete="off" type="search" placeholder="Search" aria-label="Search" 
				value="{{Request::get('item')?Request::get('item'):'' }}">
			</div>
		</form>
    <div id="search-items2" class="">
		@include('appl.pages.blocks.productlist')
	</div>
	</div>
  </div>

   <div class="tab-pane fade" id="mytracks" role="tabpanel" aria-labelledby="mytrack-tab">
  	<div class="mt-4">
  		<div class="border rounded p-3 bg-light mb-3">
  			List of sessions attended by the user
  		</div>
    <div id="search-items2" class="">
		@include('appl.pages.blocks.trackslist')
	</div>
	</div>
  </div>
 
</div>

</div>