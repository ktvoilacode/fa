
 @if($objs->total()!=0)
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th scope="col">#({{$objs->total()}})</th>
                <th scope="col">ID No </th>
                <th scope="col">File </th>
                <th scope="col">Feedback</th>
                <th scope="col">Created </th>
              </tr>
            </thead>
            <tbody>
              @foreach($objs as $key=>$obj)  
              <tr>
                <th scope="row">{{ $objs->currentpage() ? ($objs->currentpage()-1) * $objs->perpage() + ( $key + 1) : $key+1 }}</th>
                <td>
                  @if($obj->user)
                  <a href="{{ route('user.show',$obj->user->id) }}">
                  {{ $obj->user->idno}}
                  </a>
                  @else
                  {{$obj->session_id}}
                  @endif
                  
                  
                </td>
                <td>
                  @if(request()->get('type')=='duolingo')
                  <a href="{{ route('test.analysis',$obj->test->slug)}}?user_id={{$obj->user_id}}&admin=1">
                  @else
                  <a href=" {{ route($app->module.'.show',$obj->id) }} ">
                  @endif
                  {{ $obj->user->name}} - {{ $obj->test->name }} - Response

                  @if($obj->premium)<span class="badge badge-primary">pro</span>@endif
                  </a>
                </td>
                <td>
                 
                  @if($obj->answer || Storage::disk('public')->exists('feedback/feedback_'.$obj->id.'.pdf'))
                    <span class="badge badge-success">Reviewed</span>
                  @else
                    <span class="badge badge-secondary">Open</span>
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
