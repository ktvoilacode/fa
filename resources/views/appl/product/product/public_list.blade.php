@if(count($objs)!=0)
@foreach($objs as $obj)
@if($obj->status)
<div class="col-6 col-md-4 col-lg-3">
  <a href="{{ route('product.view',$obj->slug)}}" class="nostyle">
  <div class="p-3 hover-bg mb-3 ">
    <div class="card-body text-center">
      @if(\Storage::disk('public')->exists($obj->image) && $obj->image )
      
      <img src="{{ asset('storage/'.$obj->image) }}" class="w-50 "/>
    
      @endif
    </div>
    <div class=" text-center text-dark "><b>{!! $obj->name !!}</b></div>

  </div>
  </a>
</div>

@endif
@endforeach
@else
<div class="container">
<div class="card card-body bg-light">
  No {{ $app->module }} found
</div>
</div>
@endif