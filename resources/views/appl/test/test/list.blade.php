
 @if($objs->total()!=0)
        <div class="table-responsive bg-white">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th scope="col">#({{$objs->total()}})</th>
                <th scope="col">@sortablelink('name') </th>

                <th scope="col">@sortablelink('slug')</th>
                <th scope="col">Client</th>
                <th scope="col">@sortablelink('status')</th>
                <th scope="col">Product</th>
                <th scope="col">@sortablelink('created_at','Created')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($objs as $key=>$obj)  
              <tr>
                <th scope="row">{{ $objs->currentpage() ? ($objs->currentpage()-1) * $objs->perpage() + ( $key + 1) : $key+1 }}</th>
                <td>
                  <a href=" {{ route($app->module.'.show',$obj->id) }} ">
                  {{ $obj->name }}
                  </a>
                </td>
                <td>
                  {{$obj->slug}}
                </td>
                <td>
                  {{$obj->client_slug}}
                </td>
                <td>
                  @if($obj->status==0)
                    <span class="badge badge-warning">Inactive</span>
                  @elseif($obj->status==1)
                    <span class="badge badge-success">Active</span>
                    @elseif($obj->status==2)
                    <span class="badge badge-warning">Open</span>
                    @elseif($obj->status==3)
                    <span class="badge badge-warning">Private</span>
                  @endif
                </td>
                <td>
                  @foreach($obj->products as $p)
                    <a href="{{route('product.show',$p->id)}}">{{strip_tags($p->name)}}</a><br>
                  @endforeach
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
