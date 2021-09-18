@foreach($obj->tests as $k=>$test)
@if($test->status)
<div class=" 
mb-3 test_block" style="@if($k>2)display:none;@endif">
<div class="card" style="box-shadow: 2px 3px #f8f9fa;background-image: linear-gradient(#fbf0f2 5%, white 80%,white 15%); " >

  <div class="card-body">
    <div class="d-none d-md-block float-right">
    @include('appl.product.product.blocks.test_button')
    </div>

    <h4 class="card-title"><i class="fa fa-clone"></i> {{ $test->name}} 

      @if($test->price==0)
      <span class="badge badge-warning">FREE</span>
      @endif
     </h4>

    @if($test->marks)
    <div class="">{{$test->marks}} Questions 
      @if($test->test_time)
      | {{$test->test_time}} min
      @endif
    </div>
    @elseif($test->test_time)
    <div class="">{{$test->test_time}} min</div>
    @else
    @endif

    @if($test->level)
    <div class="">
      <B>Level : </B>

      <span class="text-success">
        @for($i=$test->level;$i>0;$i--)
        <i class="fa fa-circle "></i>
        @endfor
      </span>
      <span class="text-secondary">
        @for($i=(5-$test->level);$i>0;$i--)
        <i class="fa fa-circle-o "></i>
        @endfor
      </span>
    </div>
    @endif

    <div class="d-block d-md-none mt-4">
    @include('appl.product.product.blocks.test_button')
    </div>

  </div>
</div>
</div>
@endif
@endforeach