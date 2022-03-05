
 @if($objs->total()!=0)
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th scope="col">#({{$objs->total()}})</th>
                <th scope="col">@sortablelink('idno') </th>
                <th scope="col">@sortablelink('name') </th>
                <th scope="col">@sortablelink('email') </th>
                <th scope="col">@sortablelink('phone') </th>
                <th scope="col">@sortablelink('user_id','Created By')</th>
                <th scope="col">@sortablelink('created_at') </th>
                 <th scope="col">Comment </th>
              </tr>
            </thead>
            <tbody>
              @foreach($objs as $key=>$obj)  
              <tr>
                <td scope="row">{{ $objs->currentpage() ? ($objs->currentpage()-1) * $objs->perpage() + ( $key + 1) : $key+1 }}</td>
                <td>
                  {{ $obj->idno }}
                </td>
                <td>
                  <a href=" {{ route($app->module.'.show',$obj->id) }} ">
                  {{ $obj->name }}
                  </a>
                </td>
                <td>
                  {{ $obj->email }}
                </td>
                <td>
                  {{ $obj->phone }}
                </td>
                <td>
                  @if($obj->user_id)
                  @if(isset($obj->referral($obj->user_id)->name))
                    <span class="badge badge-success">{{ $obj->referral($obj->user_id)->name }}</span>
                  @endif
                  @else
                    <span class="badge badge-primary"> None</span>
                  @endif
                </td>
                <td>{{ ($obj->created_at) ? $obj->created_at->diffForHumans() : '' }}</td>
                <td>{{ ($obj->comment) ? $obj->comment : '' }}</td>
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
