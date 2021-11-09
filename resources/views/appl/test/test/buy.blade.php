
@auth
	@if(1)
	<a href="{{ route('product.checkout',$obj->slug) }}">
		<button class="btn btn-lg btn-success mt-3">Buy Now</button>
	</a>
	@else
	<button type="button" class="btn btn-lg btn-success mt-3" type="button" data-toggle="modal" data-target="#exampleModal ">Buy Now</button>
	@endif
@else
	<button type="button" class="btn btn-lg btn-success mt-3" type="button" data-toggle="modal" data-target="#exampleModal ">Buy Now</button>
@endauth


@auth

	@if(1)
	<a href="{{ route('product.checkout-access',$obj->slug) }}">
	<button class="btn btn-lg btn-outline-primary mt-3">Access Code</button>
	</a>
	@else
	<button type="button" class="btn btn-lg btn-outline-primary mt-3" type="button" data-toggle="modal" data-target="#exampleModal ">Access Code</button>
	@endif

@else
	<button type="button" class="btn btn-lg btn-outline-primary mt-3" type="button" data-toggle="modal" data-target="#exampleModal ">Access Code</button>
@endauth

@include('blocks.loginmodal')
