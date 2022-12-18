
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Krishna Teja G S">
    <title>@yield('title')</title>

   <link href="{{ asset('css/app.css') }}" rel="stylesheet">
   <link href="{{ asset('css/cover.css') }}" rel="stylesheet">

  </head>
  <body class="text-center">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">

  <main role="main" class="inner cover mt-5">
    @yield('content')
  </main>
  <footer class="mastfoot mt-auto">
    <div class="inner">
      <p>
Copyright © <a href="{{ url('/')}}">Piofx</a>. All Rights Reserved. Designed and developed by Piofx</p>
    </div>
  </footer>
</div>
</body>
</html>
