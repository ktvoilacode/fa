@auth
<nav class="navbar navbar-expand-md navbar-light  shadow-sm"  style="background:#fff" >
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img class="" src="@if(request()->session()->get('client')) {{ request()->session()->get('client')->logo }} @else {{ asset('images/gradable.png') }} @endif" alt="Piofx" width="100" >
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/home') }}"><i class="fa fa-home"></i> Dashboard </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/tests') }}"><i class="fa fa-check-square-o"></i> Test Packs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/testhistory') }}"><i class="fa fa-list-alt"></i>  Test History </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/user/edit') }}?p=1"><i class="fa fa-user-circle-o"></i>  Profile </a>
                </li>
                @if(\auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin') }}"><i class="fa fa-adn"></i> Admin</a>
                </li>
                @endif

                <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"> <i class="fa fa-sign-out"></i>
                            {{ __('Logout') }}
                        </a>
                </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                @endauth
                @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}"><i class="fa fa-sign-in"></i> {{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}"><i class="fa fa-user-plus"></i> {{ __('Register') }}</a>
                </li>
                @endif
                @else

                
                @endguest
            </ul>
        </div>
    </div>
</nav>
@endauth