@extends('layouts.clean')
@section('title', 'Writing Limit - '.$test->name)
@section('description', 'These are the test instructions of the test '.$test->name)


@section('content')
<div  class="row p-3">
  <div class="col-md-12">
    <div class=" bg-light p-3 border mb-3 " style="word-wrap: break-word;
    "><div class="h4 mt-2" style="word-wrap: break-word;
    "><i class="fa fa-exclamation-circle"></i> Writing Submission Limit  </div> 
  </div>
  <div class="card">
    <div class="card-body mb-0">
      
      <div class="mb-3 " style="font-size: 18px;">
        The writing submissions are restricted to <span class="badge badge-warning">two tasks</span> in 24 hours. <br>Kindly attempt the test after some time.<br><br>

        <div class="mb-3">
          <table class="table table-bordered ">
  <thead class="thead-light">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Test</th>
      <th scope="col">Attempted</th>
    </tr>
  </thead>
  <tbody>
     @foreach($wattempt as $k=>$w)
            
             <tr>
            <th scope="row">{{($k+1)}}</th>
      <td>{{ $w->test->name }}</td>
      <td>{{ $w->created_at->diffForHumans() }}</td>
      </tr>
          @endforeach
   
      
    
  </tbody>
</table>
         
        </div>
      </div>
      
    </div>
  </div>
</div>
</div>
@endsection


