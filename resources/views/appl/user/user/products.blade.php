@if(count($obj->orders)>0)
       <div class="card ">
        <div class="card-body">
          <div class="card-title">
          <h3>Products/Tests</h3>
        </div>
          <div class="table-responsive">
            <table class="table table-bordered mb-0 border">
              <thead>
                <tr class="bg-light">
                  <th scope="col">#</th>
                  <th scope="col" >Product/Test</th>
                  <th scope="col" class="w-25">Order ID / Valid till</th>
                  
                  <th scope="col" >Coupon / Referral</th>

                </tr>
              </thead>
              <tbody>
                @foreach($obj->orders as $k=>$order)
                  <tr>
                      <td>{{$k+1}}</td>
                      <td>
                        @if(isset($order->test->id) && isset($obj->id))
                        <a href="{{ route('user.test',[$obj->id,$order->test->id])}}">
                        {{strip_tags($order->test->name)}}  
                        </a>
                        @if($obj->attempted($obj->id,$order->test->id))
                        <span class="badge badge-secondary">attempted</span>
                        @endif
                        {{$obj->testscore($obj->id,$order->test->id)}}
                      
                        @else

                        @if(isset($order->product->name))
                        {{ strip_tags($order->product->name)}}
                        <ul>
                          @foreach($order->product->tests as $test)
                            <li><a href="{{ route('user.test',[$obj->id,$test->id])}}">{{ $test->name }}
                              
                            </a>

                            @if($obj->has_attempted($test->id))
                            <span class="badge badge-secondary">attempted</span>
                            @endif

                            {{$obj->get_testscore($obj->id,$test->id)}}</li>
                          @endforeach
                        </ul>
                        @endif

                        @endif
                      </td>
                      <td><a href="{{ route('order.show',[$order->id])}}">{{$order->order_id}}</a>
                        / {{ date('d M Y', strtotime($order->expiry))}}
                      </td>
                      
                      <td>{{$order->txn_id}}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      @endif