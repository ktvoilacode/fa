<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{request()->session()->get('client')->name}}</title>
  <meta name="description" content="An advanced assessments platform designed and developed by an expert team.">
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link rel="shortcut icon" href="{{asset('/favicon_client.ico')}}" />
</head>
<body>
  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6 login-section-wrapper ">
          <div class="brand-wrapper">
            <img  src="{{ request()->session()->get('client')->logo }} "  class="ml-md-0 @if(domain()=='gradable') w-50 @else w-100 @endif mb-5"  alt=" logo " type="image/png" style="max-width:250px">
          </div>
          @if(request()->session()->get('config'))
            @if(request()->session()->get('config')->message_l)
              <div class="alert alert-warning alert-important mt-3">
                <div class=" h5 mt-2">{{request()->session()->get('config')->message_l}}</div>
                @if(request()->session()->get('config')->timer_l)
                 <p id="d" class="my-2 text-danger blink countdown_timer" data-timer="{{request()->session()->get('config')->timer_l}}"></p>
                @endif
              </div>
            @endif
          @endif
          
          <div class=" my-auto">
             @include('auth.pages.login')
             <div class="mt-5">
              <div class="p-2"></div>
             <hr >
             Incase of any query you can reach out to our resource person, details in the <a href="{{ route('contactpage')}}">contact page</a>
           </div>
          </div>
        </div>
       
       
        <div class="col-sm-6 px-0 d-none d-sm-block">
          @if(Storage::disk('s3')->exists('clients/'.request()->session()->get('client')->slug.'_login.png'))
          <img src="{{ Storage::disk('s3')->url('clients/'.request()->session()->get('client')->slug.'_login.png')}}?time={{ microtime()}}" alt="login image" class="login-img" />
          @elseif(Storage::disk('s3')->exists('clients/'.request()->session()->get('client')->slug.'_login.jpg'))
          <img src="{{ Storage::disk('s3')->url('clients/'.request()->session()->get('client')->slug.'_login.jpg')}}?time={{ microtime()}}" alt="login image" class="login-img" />
          @else
          <img src="{{ asset('images/bg_login_gradable.jpg') }}?time={{ microtime()}}" alt="login image" class="login-img">
          @endif
        </div>
      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="{{ asset('js/client.js')}}?r=1"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
<?php session()->put( 'redirect.url',request()->url().'/dashboard'); ?>