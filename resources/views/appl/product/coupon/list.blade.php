
 @if($objs->total()!=0)
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th scope="col">#({{$objs->total()}})</th>
                <th scope="col">Code </th>
                <th scope="col">Usage</th>
                <th scope="col">Client</th>
                <th scope="col">Status</th>
                <th scope="col">Created at</th>
              </tr>
            </thead>
            <tbody>
              @foreach($objs as $key=>$obj)  
              <tr>
                <th scope="row">{{ $objs->currentpage() ? ($objs->currentpage()-1) * $objs->perpage() + ( $key + 1) : $key+1 }}</th>
                <td>
                  <a href=" {{ route($app->module.'.show',$obj->id) }} ">
                  {{ $obj->code }}
                  </a>
                </td>
                <td>
                <a href="{{ route('order.index')}}?coupon={{$obj->code}}">
                 {{ $obj->count() }}
                </a>
                </td>
                <td><a href="{{ route('coupon.index')}}?client_slug={{$obj->client_slug}}">{{ $obj->client_slug}}</a></td>
                <td>
                  @if($obj->status==0)
                    <span class="badge badge-danger">Used</span>
                  @elseif($obj->status==1)
                    <span class="badge badge-success">Active</span>
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
