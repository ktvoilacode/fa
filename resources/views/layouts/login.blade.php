<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @if($_SERVER['HTTP_HOST'] == 'onlinelibrary.test' || $_SERVER['HTTP_HOST'] == 'piofx.com' )
      <link rel="shortcut icon" href="{{asset('/favicon_piofx.ico')}}" />
      @else
      <link rel="shortcut icon" href="{{asset('/favicon.ico')}}" />
      @endif
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffc40d">
    <meta name="theme-color" content="#ffffff">
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="author" content="First Academy Team">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Styles -->
    @if($_SERVER['HTTP_HOST'] == 'project.test' || $_SERVER['HTTP_HOST'] == 'prep.firstacademy.in')
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    @else
    <link href="{{ asset('css/styles_piofx.css') }}" rel="stylesheet">
    @endif
    
  </head>
  <body class="py-5 text-center">
       @yield('content')
  </body>
  @include('layouts.script')
</html>

