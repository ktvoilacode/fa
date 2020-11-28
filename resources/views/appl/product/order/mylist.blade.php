
 @if($objs->total()!=0)
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Order ID</th>
                <th scope="col">Tests</th>
                <th scope="col">Type</th>
                <th scope="col">Status</th>
                <th scope="col">Created At</th>
              </tr>
            </thead>
            <tbody>
              @foreach($objs as $key=>$obj)  
              <tr>
                <th scope="row">{{ $objs->currentpage() ? ($objs->currentpage()-1) * $objs->perpage() + ( $key + 1) : $key+1 }}</th>
                <td>
                  <a href=" {{ route('myorder.view',$obj->order_id) }} ">
                  {{ $obj->order_id }}
                  </a>
                </td>
                <td>
                @if($obj->test_id)
                  @if(isset($obj->test->name))
                  {{ strip_tags($obj->test->name) }} 
                  @else
                  -
                  @endif
                @else
                  @if(isset($obj->product->name))
                  {{ strip_tags($obj->product->name) }} 
                  @else
                  -
                  @endif
                
                @endif
                </td>
                <td>
                  @if($obj->test_id)
                    @if(isset($obj->test->name))
                      @if($obj->test->price==0)
                      <span class="badge badge-warning">Free</span>
                      @else
                      <span class="badge badge-primary">Premium</span>
                      @endif
                    @else
                    -
                    @endif
                    
                  @else
                    @if(isset($obj->product->name))
                      @if($obj->product->price==0)
                      <span class="badge badge-warning">Free</span>
                      @else
                      <span class="badge badge-primary">Premium</span>
                      @endif
                    @else
                    -
                    @endif
                    
                  @endif
                </td>
                
                <td>
                  @if($obj->status==0)
                    <span class="badge badge-warning">Incomplete</span>
                  @elseif($obj->status==1)
                    <span class="badge badge-success">Successful</span>
                  @endif
                </td>
                <td>{{ ($obj->created_at) ? $obj->created_at->diffForHumans() : '' }}</td>
              </tr>
              @endforeach      
            </tbody>
          </table>
        </div>
        @else
        <div class="card card-body bg-light">
          No {{ $app->module }} found
        </div>
        @endif
        <nav aria-label="Page navigation  " class="card-nav @if($objs->total() > config('global.no_of_records'))mt-3 @endif">
        {{$objs->appends(request()->except(['page','search']))->links()  }}
      </nav>
