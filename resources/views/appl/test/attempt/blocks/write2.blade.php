
@include('flash::message')
<h3 >Write your response in the textbox below.</h3>
<div class="mb-4  mt-3 bg-light p-4 border">
	
	<textarea class="summernote2 formcontrol" name="response_2">
		
	</textarea>
	<a href="#" class="btn btn-success btn-lg  mt-3" data-toggle="modal" data-target="#exampleModal2" data-tooltip="tooltip" data-placement="top" title="Delete" >Submit </a>
		
		<div class="float-right mt-4 text-secondary d-none"><span class="word-count">0</span> words</div>

</div>


@auth
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Accept Terms </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul>
          <li>Tasks once submitted cannot be changed.</li>
          <li>Evaluation will take at least <span class="badge badge-warning">&nbsp;2&nbsp;</span> working days.</li>
          <li> Tasks with plagiarism shall <u>not</u> be evaluated but will count as a task.</li>
          <li>One-to-One sessions for writing will be based on any <span class="badge badge-warning">&nbsp;1&nbsp;</span> task submitted here. </li>
          <li>Scores are indicative and are purely for training purposes.</li> 
        </ul>
        <div class="p-3 rounded bg-warning">
       <input type="checkbox" name="accept" class="accept" > &nbsp;<b>I/We accept the terms</b></input>
     </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

	   <input type="hidden" name="_token" value="{{ csrf_token() }}">
		  <input type="hidden" name="product" value="@if($product){{ $product->slug }} @endif">
      <button type="submit" class="btn btn-success">I Accept & Submit</button>
      </div>
    </div>
  </div>
</div>
@else

<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
