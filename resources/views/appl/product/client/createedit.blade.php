@extends('layouts.app')
@include('meta.createedit')
@section('content')

@include('flash::message')

  @if($stub=='Create')
      <form method="post" action="{{route($app->module.'.store')}}" enctype="multipart/form-data">
  @else
      <form method="post" action="{{route($app->module.'.update',$obj->id)}}" enctype="multipart/form-data">
  @endif  
  <div class="card">
    <div class="card-header bgblue" style="">
      <h3 class="py-2  mb-0">
        @if($stub=='Create')
          Create {{ $app->module }}
        @else
          Update {{ $app->module }}
        @endif  
       <button type="submit" class="btn btn-success  float-right">Save</button>
       </h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="formGroupExampleInput ">{{ ucfirst($app->module)}} Name</label>
            <input type="text" class="form-control" name="name" id="formGroupExampleInput" 
                @if($stub=='Create')
                value="{{ (old('name')) ? old('name') : $obj->name }}"
                @else
                value = "{{ $obj->name }}"
                @endif
              >
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="formGroupExampleInput ">{{ ucfirst($app->module)}} Slug</label>
            <input type="text" class="form-control" name="slug" id="formGroupExampleInput" 
                @if($stub=='Create')
                value="{{ (old('slug')) ? old('slug') : $obj->slug }}"
                @else
                value = "{{ $obj->slug }}"
                @endif
              >
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="formGroupExampleInput ">Domains (seperated by commas)</label>
            <input type="text" class="form-control" name="domains" id="formGroupExampleInput" 
                @if($stub=='Create')
                value="{{ (old('domains')) ? old('domains') : $obj->domains }}"
                @else
                value = "{{ $obj->domains }}"
                @endif
              >
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
           <div class="form-group">
              <label for="formGroupExampleInput">Contact Details</label>
               <textarea  class="form-control summernote" name="contact"  rows="5">
                  @if($stub=='Create')
                  {{ (old('contact')) ? old('contact') : '' }}
                  @else
                  {{ $obj->config->contact }}
                  @endif
              </textarea>
            </div>
        </div>
      </div>

       @if($stub=='Create')
      <div class="bg-light border p-3 rounded my-3">
        <h4>Admin User (Optional)</h4>
        <hr>
        <div class="row">
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="formGroupExampleInput ">Name</label>
              <input type="text" class="form-control" name="admin_name" id="formGroupExampleInput" placeholder="Enter Name" 
                @if($stub=='Create')
                value="{{ (old('admin_name')) ? old('admin_name') : '' }}"
                @endif
              >
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="formGroupExampleInput ">Email</label>
              <input type="text" class="form-control" name="admin_email" id="formGroupExampleInput" placeholder="Enter email" 
                @if($stub=='Create')
                value="{{ (old('admin_email')) ? old('admin_email') : '' }}"
                @endif
              >
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="formGroupExampleInput ">Phone</label>
              <input type="text" class="form-control" name="admin_phone" id="formGroupExampleInput" placeholder="Enter phone" 
                @if($stub=='Create')
                value="{{ (old('admin_phone')) ? old('admin_phone') : '' }}"
                @endif
              >
            </div>
          </div>
        </div>
        <hr>
        <span class="badge badge-warning">Note</span><br> For Admin user, phone number is the password<br>A demo user with email demo500@gradable.in and password demo500 is auto generated
      </div>
      @endif
      <div class="border p-3 bg-light">
        <h3><i class="fa fa-th"></i> Settings</h3>
        <hr>
      <div class="row">
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="formGroupExampleInput ">Register Button</label>
            <select class="form-control" name="register">
              <option value="0" @if(isset($obj)) @if($obj->config->register==0) selected @endif @endif >Disabled</option>
              <option value="1" @if(isset($obj)) @if($obj->config->register==1) selected @endif @endif >Enabled</option>
            </select>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="formGroupExampleInput ">Change Password</label>
            <select class="form-control" name="change_password">
              <option value="0" @if(isset($obj)) @if($obj->config->change_password==0) selected @endif @endif >Disabled</option>
              <option value="1" @if(isset($obj)) @if($obj->config->change_password==1) selected @endif @endif >Enabled</option>
            </select>
          </div>

        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="formGroupExampleInput ">Add Users</label>
            <select class="form-control" name="add_users">
              <option value="0" @if(isset($obj)) @if($obj->config->add_users==0) selected @endif @endif >Disabled</option>
              <option value="1" @if(isset($obj)) @if($obj->config->add_users==1) selected @endif @endif >Enabled</option>
            </select>
          </div>

        </div>
       
      </div>


      <div class="row">
        <div class="col-12 col-md-4">
          <div class="form-group">
        <label for="formGroupExampleInput ">Logo</label>
        <input type="file" class="form-control" name="file_logo" id="formGroupExampleInput" placeholder="Enter the image path" 
          >
        </div>
        </div>

        <div class="col-12 col-md-4">
          <div class="form-group">
        <label for="formGroupExampleInput ">favicon</label>
        <input type="file" class="form-control" name="file_favicon" id="formGroupExampleInput" placeholder="Enter the image path" 
          >
        </div>
        </div>

        <div class="col-12 col-md-4">
          <div class="form-group">
        <label for="formGroupExampleInput ">Loginpage Image</label>
        <input type="file" class="form-control" name="file_login" id="formGroupExampleInput" placeholder="Enter the image path" >
        </div>
        </div>
        <div class="col-12 col-md-4">
           <div class="form-group">
            <label for="formGroupExampleInput ">Registerpage Image</label>
            <input type="file" class="form-control" name="file_register" id="formGroupExampleInput" placeholder="Enter the image path" >
            </div>
          </div>
     
        <div class="col-12 col-md-4">
           <div class="form-group">
            <label for="formGroupExampleInput ">Dashboard Image</label>
            <input type="file" class="form-control" name="file_dashboard" id="formGroupExampleInput" placeholder="Enter the image path" 
              >
            </div>
          </div>
       

        <div class="col-12 col-md-4">
           <div class="form-group">
            <label for="formGroupExampleInput ">Default Coupon </label>
            <input type="text" class="form-control" name="default_coupon" id="formGroupExampleInput" placeholder="Enter coupon name" 
                @if($stub=='Create')
                value="{{ (old('default_coupon')) ? old('default_coupon') : '' }}"
                @else
                value = "@if(isset($obj->config->default_coupon)) {{ $obj->config->default_coupon }} @endif"
                @endif
              >
            </div>
          </div>
        </div>
        <div class="row">
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="formGroupExampleInput">Message in Dashboard</label>
             <textarea  class="form-control summernote" name="message_d"  rows="5">
                @if($stub=='Create')
                {{ (old('message_d')) ? old('message_d') : '' }}
                @else
                {{ $obj->config->message_d }}
                @endif
            </textarea>
          </div>
          <div class="form-group">
            <label for="formGroupExampleInput ">Dashboard Countdown timer</label>
            <input id="datetimepicker" class="form-control" type="text" value="{{isset($obj->config->timer_d)? $obj->config->timer_d:''}}"  name="timer_d"></input>
          </div>

            <div class="form-group">
            <label for="formGroupExampleInput ">Instamojo Key</label>
            <input class="form-control" type="text" value="{{isset($obj->config->key)? $obj->config->key:''}}"  name="key"></input>
          </div>

        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="formGroupExampleInput">Message in Loginpage</label>
             <textarea  class="form-control summernote" name="message_l"  rows="5">
                @if($stub=='Create')
                {{ (old('message_l')) ? old('message_l') : '' }}
                @else
                {{ $obj->config->message_l }}
                @endif
            </textarea>
          </div>
          <div class="form-group">
            <label for="formGroupExampleInput ">Loginpage Countdown timer</label>
            <input id="datetimepicker2" class="form-control" type="text" value="{{isset($obj->config->timer_l)? $obj->config->timer_l:''}}"  name="timer_l"></input>
          </div>

            <div class="form-group">
            <label for="formGroupExampleInput ">Instamojo Token</label>
            <input class="form-control" type="text" value="{{isset($obj->config->token)? $obj->config->token:''}}"  name="token"></input>
          </div>


        </div>
        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="formGroupExampleInput">Message in Registerpage</label>
             <textarea  class="form-control summernote" name="message_r"  rows="5">
                @if($stub=='Create')
                {{ (old('message_r')) ? old('message_r') : '' }}
                @else
                @if(isset($obj->config->message_r )){{ $obj->config->message_r }} @endif
                @endif
            </textarea>
          </div>
          <div class="form-group">
            <label for="formGroupExampleInput ">Registerpage Countdown timer</label>
            <input id="datetimepicker3" class="form-control" type="text" value="{{isset($obj->config->timer_r)? $obj->config->timer_r:''}}"  name="timer_r"></input>
          </div>

            


        </div>



    </div>
    <div class="row">
        <div class="col-12 ">
          <div class="form-group">
              <label for="formGroupExampleInput">Register page html form feilds</label>
               <textarea  class="form-control " name="rform"  rows="5">
                  @if($stub=='Create')
                  {{ (old('rform')) ? old('rform') : '' }}
                  @else
                  @if(isset($obj->config->rform ))
                  {{ $obj->config->rform }}
                  @endif
                  @endif
              </textarea>
            </div>
        </div>
      </div>
   
      
    </div>

  


      <div class="row">
       
        <div class="col-12 ">
          
           <div class="form-group mt-4">
            <label for="formGroupExampleInput">Products</label>
             <div class=" card p-3">
              <div class="row">
              @foreach($products as $product)
              @if($product->status==1)
              <div class="col-12 col-md-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="product[]" value="{{$product->id}}" id="defaultCheck1" @if($obj->products->contains($product->id))) checked @endif>
                <label class="form-check-label" for="defaultCheck1">
                  {!! $product->name !!}
                </label>
              </div>
            </div>
              @endif
              @endforeach
              </div>
             </div>
          </div>
          

          <div class="form-group">
            <label for="formGroupExampleInput ">Status </label>
            <select class="form-control" name="status">
              <option value="0" @if(isset($obj)) @if($obj->status==0) selected @endif @endif >Unpublished</option>
              <option value="1" @if(isset($obj)) @if($obj->status==1) selected @endif @endif >Published</option>
              <option value="2" @if(isset($obj)) @if($obj->status==2) selected @endif @endif >Suspended</option>
              <option value="3" @if(isset($obj)) @if($obj->status==3) selected @endif @endif >Terminated</option>
            </select>
          </div>
          
        </div>
      </div>
      
          @if($stub=='Update')
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{ $obj->id }}">
      @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <button type="submit" class="btn btn-success btn-lg">Save</button>
      
    </div>
  </div>
  
    </form>
@endsection