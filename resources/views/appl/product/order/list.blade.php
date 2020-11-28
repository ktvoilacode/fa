
 @if($objs->total()!=0)
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Order ID</th>
                <th scope="col">User </th>
                <th scope="col">Product/Tests</th>
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
                  <a href=" {{ route($app->module.'.show',$obj->id) }} ">
                  {{ $obj->order_id }}
                  </a>
                </td>
                <td>
                  <a href=" {{ route('user.show',$obj->user->id) }} ">
                  
                {{ $obj->user->name }} 
               </a>
                </td>
                <td>
                @if($obj->test_id)
                  @if(isset($obj->test->name))
                  {{ $obj->test->name }} 
                  @else
                  -
                  @endif
                @else
                {{ $obj->product->name }} 
                @endif
                </td>
                <td>
                  @if($obj->test_id)
                    @if($obj->test->price==0)
                    <span class="badge badge-warning">Free</span>
                    @else
                    <span class="badge badge-primary">Premium</span>
                    @endif
                  @else
                    @if($obj->product->price==0)
                    <span class="badge badge-warning">Free</span>
                    @else
                    <span class="badge badge-primary">Premium</span>
                    @endif
                  @endif
                </td>
                <td>
                  @if($obj->status==0)
                    <span class="badge badge-warning">Open</span>
                  @elseif($obj->status==1)
                    <span class="badge badge-success">Completed</span>
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
