@if(\auth::user())
  @if($order = $obj->order(\auth::user()))
      @if(time()>strtotime($order->expiry))
        <div class="border p-3 rounded mb-3">
          <i class="fa fa-times-circle text-danger"></i> Your service is expired  on <span class="text-secondary">{{ date('d-m-Y',strtotime($order->expiry)) }}</span>
        </div>
        @if($obj->price !=0)
          @include('appl.test.test.buy')
        @else
          <a href="{{ route('test.instructions',$obj->slug) }}?grantaccess=1">
            <button class="btn btn-lg btn-success" type="button">Access Now</button>
          </a>
        @endif
      @else
        <div class="border p-3 rounded mb-3">
          <i class="fa fa-check-circle text-success"></i> Your service is activated <span class="text-secondary">{{ $order->created_at->diffForHumans()}}</span>
        </div>
         @if(!\auth::user()->attempt($obj->id))
            <a href="{{ route('test.instructions',$obj->slug) }}">
              <button class="btn btn-lg btn-success">Try Now</button>
            </a>
          @else
            @if($test->testtype->name == 'SPEAKING' || $test->testtype->name == 'WRITING')
              <a href="{{ route('test.try',$test->slug)}}" class="btn btn-secondary mb-1"><i class="fa fa-eye"></i> View Response</a>
            @else
              <a href="{{ route('test.analysis',$test->slug)}}" class="btn btn-secondary mb-1"><i class="fa fa-bar-chart"></i> Test Report</a>
            @endif
          @endif

        @endif
            
  @else
      @if($obj->price !=0)
        <p class="h3 mb-4"><i class="fa fa-rupee"></i> {{ $obj->price}}</p>
        @include('appl.test.test.buy')        
      @elseif($obj->price===null)

      @else
        <p class="h3 mb-4"><span class="badge badge-warning">FREE</span></p>
        @auth
          <a href="{{ route('test.instructions',$obj->slug) }}?grantaccess=1">
            <button class="btn btn-lg btn-success" type="button">Access Now</button>
          </a>
        @else
          <a href="{{ route('test.instructions',$obj->slug) }}">
              <button class="btn btn-lg btn-success" type="button">Try Now</button>
          </a>
        @endauth
              
      @endif
  @endif
@else
  @if($obj->price !=0)
    <p class="h3 mb-4"><i class="fa fa-rupee"></i> {{ $obj->price}}</p>
    @include('appl.test.test.buy')
  @else
    <p class="h3 mb-4"><span class="badge badge-warning">FREE</span></p>
    @auth
      <a href="{{ route('test.instructions',$obj->slug) }}?grantaccess=1">
        <button class="btn btn-lg btn-success" type="button">Access Now</button>
      </a>
    @else
      <a href="{{ route('test.instructions',$obj->slug) }}">
        <button class="btn btn-lg btn-success" type="button">Try Now</button>
      </a>
    @endauth
  @endif
@endif