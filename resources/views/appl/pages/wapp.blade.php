@extends('layouts.app')
@section('title', 'Whatsapp | FA')

@section('content')

<div class="card">
	<div class="card-body">
		@include('flash::message')
		<h1>Whatsapp Form</h1>
		<form method="post" class="url_codesave" action="{{route('whatsapp')}}" enctype="multipart/form-data">
		   <div class="form-group bg-light border p-4">
            <label for="exampleFormControlFile1">Upload File</label>
            <input type="file" class="form-control-file" name="file" id="exampleFormControlFile1">
          </div>
		  <div class="form-group">
		  	<div><b> Template</b></div>
		  	<select class="form-control" name="template">
			  <option value="couponcode">Coupon Code</option>
			  <option value="new_product">New Product</option>
			  <option value="promo1">IELTS Promo 1</option>
			  <option value="result">Result Message</option>
			</select>
		  </div>

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
		   <button type="submit" class="btn btn-primary mb-2">Send</button>
		</form>
		


	</div>

</div>
<div class="border p-4 rounded mt-4">
			<h3> Sample CSV Files</h3>
			<ul>
				<li>Coupon code file - <a href="/wtemplate_coupon.csv">download</a></li>
				<li>New Product file - <a href="/wtemplate_product.csv">download</a></li>
				<li>IELTS promo file - <a href="/wtemplate_ielts.csv">download</a></li>
				<li>Result Message file - <a href="/wtemplate_result.csv">download</a></li>
			</ul>
		</div>
@endsection           