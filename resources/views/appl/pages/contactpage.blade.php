
@extends('layouts.app')
@section('title', 'Contact Us - '.client('name'))
@section('content')

<div class="bg-white">
<div class="card-body p-4 ">	
  <div class="p-3">
    <h1>Contact Us</h1>
    <div class="bg-info pt-1 rounded w-25 mb-4"></div><br>
    {{client('contact')}}<br><br>
    <a href="/" class="btn btn-primary">back to homepage</a>
  </div>
 




</div>		
</div>
@endsection           