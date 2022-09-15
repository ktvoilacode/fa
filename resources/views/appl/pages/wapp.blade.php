@extends('layouts.app')
@section('title', 'Whatsapp | FA')

@section('content')

<div class="card">
	<div class="card-body">
		<h1>Whatsapp Form</h1>
		<form>
		  <div class="form-group">
		    <label for="exampleInputEmail1">Enter Phone numbers (seperated commas)</label>
		    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter phone number">
		  </div>
		  <div class="form-group">
		    <label for="exampleFormControlTextarea1">Message</label>
		    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
		  </div>
		   <button type="submit" class="btn btn-primary mb-2">Send</button>
		</form>
	</div>
</div>
@endsection           