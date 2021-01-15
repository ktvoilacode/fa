<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">


    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Rokkitt:wght@300;400;500;600;700&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@400;500;700&display=swap" rel="stylesheet"> 

    <style>

      html, body{
    background: #6ab42b;
    font-family: 'Rokkitt', serif;
}

.secondary_font{
    font-family: 'Mulish', sans-serif;
}

.img_shadow{
    filter: drop-shadow(1px 1px 3px #385f16);
}

.main_container{
    display: flex;
    justify-content: center;
    align-items: center;
}

.main{
    width: 60%;
    display: grid;
    grid-template-columns: 50% 50%;
    justify-items: center;
    align-items: center;
    margin: 5rem 0;
}

.main_image{
    text-align: center;
}

.main_image img{
    width: 60%;
}

.footer{
    width: 100%;
    display: flex;
    justify-content: center;
    margin: 5rem 0;
}

@media only screen and (max-width: 991.98px){
    .main{
        display: inline-block;
        margin-bottom: 2rem;
    }

    .main_text{
        text-align: center;
    }

    .main_image img{
        width: 100%;
    }

    .footer{
        display: block;
        margin: 0 0 3rem 0;
    }

    .footer div{
        margin: 2rem 0;
    }
}</style>

    <title>First Academy</title>
</head>
<body>
    <div class="p-4">
        <a href="{{ url('/')}}"><img src="./images/logo_white.png" alt="First Academy" width="200"></a>


    </div>

    <div class="main_container">
        <div class="main">
            <div class="main_text">
                <h1 class="fw-bold text-white display-2 lh-1">10 Duolingo <br> Practice Tests</h1>
                <p class="lh-1 fs-4">Ten, full, scored, independent tests <br> simulating the Duolingo English Test</p>

                @auth
                  @if(\auth::user()->sms_token==1)
                  <a href="{{ route('product.checkout','10detft') }}">
                    <button class="btn btn-lg btn-light secondary_font fw-bold">Buy Now</button>
                  </a>
                  @else
                  <button type="button" class="btn btn-lg btn-light secondary_font fw-bold" type="button" data-toggle="modal" data-target="#exampleModal ">Buy Now</button>
                  @endif
                @else
                  <button type="button" class="btn btn-lg btn-light secondary_font fw-bold" type="button" data-toggle="modal" data-target="#exampleModal ">Buy Now</button>
                @endauth
                <a href="{{ url('/') }}">
                    <button class="btn btn-lg btn-outline-light secondary_font fw-bold">Homepage</button>
                  </a>
            </div>
            <div class="main_image">
                <img src="./images/main_bird.png" class="mt-5 mt-lg-0 img_shadow">
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="text-center">
            <img src="./images/bird-1.png" class="img_shadow pb-2" width="90">
            <h4>Designed to work on Mobile<span class="px-3 d-none d-lg-inline-block">|</span></h4>
        </div>
        <div class="text-center">
            <img src="./images/bird-2.png" class="img_shadow pb-2" width="90">
            <h4>All Question Types<span class="px-3 d-none d-lg-inline-block">|</span></h4>
        </div>
        <div class="text-center">
            <img src="./images/bird-3.png" class="img_shadow pb-2" width="90">
            <h4>Optional Evalutaion available</h4>
        </div>
    </div>
    <script type="application/javascript" src="{{asset('js/jquery.js')}}"></script>  
<script type="application/javascript" src="{{asset('js/script.js?new=11')}}"></script>  
<script type="application/javascript" src="{{asset('js/jquery.form.js')}}"></script> 
<script type="application/javascript" src="{{asset('js/global.js?new=4')}}"></script>  
    @include('blocks.loginmodal')
</body>
</html>