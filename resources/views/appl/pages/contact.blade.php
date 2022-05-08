
@extends('layouts.app')
@section('title', 'Contact Us ')
@section('content')


<div class="bg-white">
<div class="card-body p-4 ">


<div class="row">
	<div class="col-12 col-md ">
		<iframe class="w-100 mb-4" height="200" frameborder="0" style="border:0"
src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJx7TARMmQyzsR8BKHnJbVVh4&key=AIzaSyCdRZQkwLoQV_3BtOBWfJA93Thke_iza8Y" allowfullscreen></iframe>

<div class="p-3">
<h1>Contact Us</h1><br>
<div class="row">
  <div class='col-12 col-md-5'>
    <h3>Ameerpet</h3>
    <div class="bg-info pt-1 rounded w-25 mb-4"></div>
707 - 708, Seventh floor<br>
Pavani Prestige, <br>
Ameerpet, Hyderabad,<br>
Telengana, 500016 <br><br>
Phone: +91 98 666 88 666<br>
Email: info@firstacademy.in<br>
  </div>
   <div class='col-12 col-md-7'>
    <h3>Madhapur</h3>
    <div class="bg-info pt-1 rounded w-25 mb-4"></div>
2nd Floor, Oyester Uptown,<br>
Beside Durgam Cheruvu Metro Station, <br>
Opposite Krishe Sapphire,<br>
Hitech City Road.<br><br>
Phone: +91 93 93 93 2938<br>
Email: madhapur@firstacademy.in<br>

  </div>
</div>
		
</div>
	</div>

	<div class="col-12 col-md-5 d-none">
		<div class="bg-light border p-4">
      @include('flash::message')
			<h1> Write to us</h1>
			<form  method="post" action="{{route('admin.contact')}}">
				<div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" class="form-control" id="exampleInputName" aria-describedby="nameHelp" name="name" placeholder="Enter Name" value="@if(\auth::user()) {{\auth::user()->name}} @endif">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" placeholder="Enter email" value="@if(\auth::user()) {{\auth::user()->email}} @endif">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Phone Number</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="phone" placeholder="Enter phone number" value="@if(\auth::user()) {{\auth::user()->phone}} @endif">
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Message</label>
    <textarea class="form-control" name="message" id="exampleFormControlTextarea1" rows="7"></textarea>
  </div>
  <div class="form-group border p-3 bg-white">
    <label for="exampleInputEmail1">Solve this:</label>
    <h4>{{$a}} + {{$b}} = </h4>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="result" placeholder="Enter the result" >
  </div>
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
		</div>
	</div>

	
</div>



</div>		
</div>
@endsection           