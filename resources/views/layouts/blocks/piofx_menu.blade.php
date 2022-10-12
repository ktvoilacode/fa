@auth
<nav class="navbar navbar-expand-md navbar-light  shadow-sm"  style="background:#fff" >
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img class="" src="@if(request()->session()->get('client')) {{ request()->session()->get('client')->logo }} @else {{ asset('images/piofx.png') }} @endif" alt="Piofx" width="100" >
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

               

                @if(\auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin') }}"><i class="fa fa-adn"></i> Admin</a>
                </li>
                @endif
                
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>
                        
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('myorders') }}"
                           >
                            My Orders
                        </a>
                       
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
@endauth