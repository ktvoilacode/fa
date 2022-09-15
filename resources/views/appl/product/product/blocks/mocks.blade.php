@foreach($obj->mocks as $k=>$mock)
@if($mock->status)
<div class=" 
mb-3 test_block" style="@if($k>2)display:none;@endif">
<div class="card" style="box-shadow: 2px 3px #f8f9fa;background-image: linear-gradient(#fbf0f2 5%, white 80%,white 15%); " >

  <div class="card-body">
    <div class="d-none d-md-block float-right">
    @if($mock->status)

     <a href="{{ route('mockpage',$mock->slug)}}" class="btn btn-primary mb-1 "><i class="fa fa-paper-plane"></i> View Mocktest</a>
    @endif
    </div>
    <h4 class="card-title"><i class="fa fa-clone"></i> {{ $mock->name}} 
    

    

  </div>
</div>
</div>
@endif
@endforeach