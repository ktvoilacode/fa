@extends('layouts.app')
@section('title',' Attach Product ')
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb border">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item">{{ ucfirst($app->module) }}</li>
  </ol>
</nav>

@include('flash::message')


<div  class="card">

  <div class="card-body ">
 
    <form method="post" class="url_codesave" action="{{route('product.upload')}}" enctype="multipart/form-data">
     <div class="form-group bg-light border p-4">
            <label for="exampleFormControlFile1">Upload File</label>
            <input type="file" class="form-control-file" name="file" id="exampleFormControlFile1">
          </div>
       
     
      
      
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

      
         <button type="submit" class="btn btn-info">Save</button>

         <div class="mt-3"><a href="{{ route('product.index')}}" class="">back to products page</a></div>
       </form>
 </div>
 
</div>

<div class="border p-4 rounded mt-4">
      <h3> Sample CSV Files</h3>
      <ul>
        <li>Product attach file - <a href="/product_attach.csv">download</a></li>
      </ul>
    </div>

@endsection


