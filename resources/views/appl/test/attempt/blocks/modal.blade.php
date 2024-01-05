@auth

@if($test->status==3)

  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <input type="hidden" name="source_product" value="{{ request()->get('product') }}">
        
        <input type="hidden" name="private" value="{{ request()->get('private') }}">
        <input type="hidden" name="open" value="1">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" id="submit_btn" class="btn btn-success">Confirm Submission</button>
      </div>
    </div>
  </div>
</div>
@else
<div class="modal fade f2" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm Submission</h5>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" id="submit_btn" class="btn btn-success submit">Submit</button>
      </div>
    </div>
  </div>
</div>
@endif
@else

@if($test->status==2)
<div class="modal fade f2" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm Submission</h5>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" id="submit_btn" class="btn btn-success submit">Submit</button>
      </div>
    </div>
  </div>
</div>
@elseif($test->status==3)

  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <input type="hidden" name="source" value="{{ request()->get('source') }}"><input type="hidden" name="source_product" value="{{ request()->get('product') }}">
        <input type="hidden" name="private" value="{{ request()->get('private') }}">
        <input type="hidden" name="open" value="1">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" id="submit_btn" class="btn btn-success">Confirm Submission</button>
      </div>
    </div>
  </div>
</div>


@else
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <div class="form-group ">
          <label >Name</label>
          <input type="text" class="form-control" name="name" placeholder="Enter name" @if(\auth::user()) value="{{\auth::user()->name}}" @endif>
        
        </div>
        <div class="form-group">
          <label >Phone Number</label>
          <input type="text" class="form-control" name="phone"  placeholder="Enter phone" @if(\auth::user()) value="{{\auth::user()->phone}}" @endif>
        </div>
        <div class="form-group">
          <label >Email address</label>
          <input type="email" class="form-control" name="email" placeholder="Enter email" @if(\auth::user()) value="{{\auth::user()->email}}" @endif>
        </div>
        <div class="form-group">
          <label>Question Number</label>
          <input type="email" class="form-control" name="qno"  placeholder="Enter question number">
        </div>
        <div class="form-group">
          <label >Details</label>
          <textarea class="form-control details"  rows="3" name="details"></textarea>
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