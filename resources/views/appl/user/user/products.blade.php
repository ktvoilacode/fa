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
                        @if($order->test_id && isset($obj->id))
                          @if(isset($tests[$order->test_id]))
                            <a href="{{ route('user.test',[$obj->id,$order->test_id])}}">
                            {{strip_tags($tests[$order->test_id]->name)}}  
                            </a>
                            @if(isset($attempts[$order->test_id]))
                            <span class="badge badge-secondary">attempted</span>
                            <span class="badge badge-warning">score - {{$attempts[$order->test_id]->sum('score')}}</span>
                            @endif
                            
                          @endif
                      
                        @else

                        @if($order->product_id)
                        @if(isset($products[$order->product_id]))
                        {{ strip_tags($products[$order->product_id]->name)}}
                        <ul>
                          
                          @foreach($products[$order->product_id]->tests as $test)
                            <li>
                              <a href="{{ route('user.test',[$obj->id,$test->id])}}">{{ $test->name }}</a>
                            @if(isset($attempts[$test->id]))
                            <span class="badge badge-secondary">attempted</span>
                             <span class="badge badge-warning">score - {{$attempts[$test->id]->sum('score')}} </span>
                            @endif
                            </li>
                          @endforeach
                          
                        </ul>
                        @endif
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