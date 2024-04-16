<div class="alert alert-warning alert-dismissible fade show alert-important" role="alert" style="display:none">
  <div class="alert-message">Hello!</div>
  <button type="button" class="close alertclose" >
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<form class="pt-3 register_form">
  <div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" class="form-control" id="name"  name="name" placeholder="Enter name">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Email</label>
    <input type="email" class="form-control" id="email"  name="email" placeholder="Enter email ">
    <small id="emailHelp" class="form-text text-muted">Gmail is preferred.</small>
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Phone</label>
    <input type="phone" class="form-control" id="phone"  name="phone" placeholder="Enter phone number">
    <!--
     <small id="emailHelp" class="form-text text-muted">OTP will be sent to this number. Kindly enter phone number with international calling extension (eg: For india +918888888888) </small>-->
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" name="password" id="exampleInputPassword" placeholder="Password">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Confirm Password</label>
    <input type="password" class="form-control" name="repassword" id="exampleInputPassword1" placeholder="Re-enter Password">
  </div>
  @if(isset($app->mhash))
  <div class="form-group">
    <label for="exampleInputPassword1">Solve this</label><br>
    <img src="/math/{{ $app->mhash}}.png" width="80px" class="mb-2"/>
    <input type="text" class="form-control" name="math" id="exampleInputPassword1" placeholder="enter your answer">
      <input type="hidden" class="form-control" name="mhash" value="{{$app->mhash}}" >
  </div>
  @endif
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  
  <button type="button" id="register_api" class="btn btn-primary register_api2" data-url="{{ route('apiregister')}}">Submit</button>
   <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
  <div class="spinner-border spinner-border-sm float-right" role="status" style="display:none">
  <span class="sr-only">Loading...</span>
</div>
</form>

<div class="otp_activation p-3 border rounded mt-3" style="display:none">
 
  <p class="mb-3 text-secondary">You will recieve an OTP in less than 1 minute. In case of any error you can reach us at +91 98666 88666</p>
    <input type="text" class="form-control mb-3" name="sms_code" id="" placeholder="Enter the OTP">
    <button type="button" id="otp_submit" class="btn btn-success otp_submit" data-url="{{ route('sms.verify')}}">Activate Account</button>
    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
    <div class="spinner-border spinner-border-sm float-right" role="status" style="display:none">
  <span class="sr-only">Loading...</span>
</div>

  </div>