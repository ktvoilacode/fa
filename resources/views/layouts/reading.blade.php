<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if($_SERVER['HTTP_HOST'] == 'onlinelibrary.test' || $_SERVER['HTTP_HOST'] == 'piofx.com' )
      <link rel="shortcut icon" href="{{asset('/favicon_piofx.ico')}}" />
      @else
      <link rel="shortcut icon" href="{{asset('/favicon.ico')}}" />
      @endif
    <!-- CSRF Token -->
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="author" content="First Academy Team">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @if(isset($player))
    <link rel='stylesheet' href='{{ asset("css/player.css") }}'>
    @endif
    <!-- Styles -->
    <link href="{{ asset('css/styles.css?new=10') }}" rel="stylesheet">
    @if(isset($editor))
    <link href="{{asset('js/summernote/summernote-bs4.css')}}" rel="stylesheet">
    @endif
    @if(isset($try) || isset($reading))
    <link rel='stylesheet' href='{{ asset("css/try.css") }}'>
    @endif
    @if(isset($try))
      <script type="text/x-mathjax-config">
          MathJax.Hub.Config({
          extensions: ["tex2jax.js"],
          jax: ["input/TeX","output/HTML-CSS"],
          tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
      });
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    @endif
    
</head>
<body>
    <div id="app">
       

        <main class="">
            @yield('content')
        </main>
        

    </div>
    @include('layouts.script')
</body>
</html>
