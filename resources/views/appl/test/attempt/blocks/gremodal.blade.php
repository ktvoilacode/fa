@auth

<div class="modal fade" id="test_submit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Test Submission</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Select the below checkbox to confirm your test submission</p>
       
        
        <div class="p-3 rounded bg-warning">
       <input type="checkbox" name="accept" class="accept" id="c1" /> &nbsp;<b><label class="form-check-label" for="c1">I Confirm</label> </b>
     </div>
      </div>
      <div class="modal-footer">
           <input type="hidden" name="name" value="{{ request()->get('name') }}">
        <input type="hidden" name="username" value="{{ request()->get('username') }}">
        <input type="hidden" name="id" value="{{ request()->get('id') }}">
        <input type="hidden" name="uri" value="{{ request()->get('uri') }}">
        <input type="hidden" name="source" value="{{ request()->get('source') }}">
        <input type="hidden" name="private" value="{{ request()->get('private') }}">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" id="submit_btn" class="btn btn-success">Confirm Submission</button>
      </div>
    </div>
  </div>
</div>


@else

  @if(isset($user))
  <div class="modal fade" id="test_submit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Test Submission</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Select the below checkbox to confirm your test submission</p>
       
        
        <div class="p-3 rounded bg-warning">
       <input type="checkbox" name="accept" class="accept" id="c1" /> &nbsp;<b><label class="form-check-label" for="c1">I Confirm</label> </b>
     </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="name" value="{{ request()->get('name') }}">
        <input type="hidden" name="username" value="{{ request()->get('username') }}">
        <input type="hidden" name="id" value="{{ request()->get('id') }}">
        <input type="hidden" name="uri" value="{{ request()->get('uri') }}">
        <input type="hidden" name="source" value="{{ request()->get('source') }}">
        <input type="hidden" name="private" value="{{ request()->get('private') }}">
        <input type="hidden" name="open" value="1">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" id="submit_btn" class="btn btn-success">Confirm Submission</button>
      </div>
    </div>
  </div>
</div>

  @else
  <div class="modal fade" id="test_submit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Login Now</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Only logged in users can submit the test. 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <a href="{{ route('login')}}">
          <button type="button" class="btn btn-success">Login</button>
          </a>
          <a href="{{ route('register')}}">
          <button type="button" class="btn btn-primary">Register</button>
          </a>
        </div>
      </div>
    </div>
  </div>
  @endif
@endauth

<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Report Error</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" class="error" action="{{ route('admin.notify')}}">
          <div class="form-group">
          <label for="exampleInputEmail1">Name</label>
          <input type="email" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter name">
        
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Email address</label>
          <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Question Number</label>
          <input type="email" class="form-control" name="qno" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter question number">
        </div>
        <div class="form-group">
          <label for="exampleFormControlTextarea1">Details</label>
          <textarea class="form-control details" id="exampleFormControlTextarea1" rows="3" name="details"></textarea>
        </div>

        

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="test" value="{{ $test->name }}">
        <input type="hidden" name="url" value="{{ route('admin.notify')}}">
        <button type="button" class="btn btn-success btn-error-report">Submit</button>
        <div class="spinner-border spinner-border-sm float-right" role="status" style="display:none">
  <span class="sr-only">Loading...</span>
</div>
      </form>
      </div>
    </div>
  </div>
</div>


@foreach($test->sections as $s=>$section)
<div class="modal fade" id="review_{{$s+1}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Review - Section {{$s+1}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Number</th>
      <th scope="col">Status</th>
      <th scope="col">Marked</th>
    </tr>
  </thead>
  <tbody>
    @foreach($section->mcq_order as $k=>$m)
    <tr>
      <th scope="row"><a href="#" class="review_qno" data-qno="{{$m->qno}}" data-sno="{{$s+1}}">{{$k+1}}</a></th>
      <td class="r_{{$s+1}}_{{$m->qno}}"><span class="badge badge-secondary">Not answered</span></td>
      <td class="m_{{$s+1}}_{{$m->qno}}"></td>
    </tr>
    @endforeach
  </tbody>
</table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
@endforeach

<div class="modal fade" id="section_submit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Section Submission</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        The following section will be submitted. After submission you cannot review or update your responses.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success btn-submit-section">Submit</button>
      </div>
    </div>
  </div>
</div>