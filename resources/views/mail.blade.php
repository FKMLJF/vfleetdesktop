
<!doctype html>
<html lang="HU">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>VFleet</title>



    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}?id={{env('ASSET_FLAG')}}">
    <!-- Material Design Bootstrap -->
    <link rel="stylesheet" href="{{asset('css/mdb.min.css')}}?id={{env('ASSET_FLAG')}}">
    <!-- Your custom styles (optional) -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}?id={{env('ASSET_FLAG')}}">


<!-- <script src="{{asset('js/jquery-3.4.1.min.js')}}" crossorigin="anonymous"></script> -->
    <script src="{{asset('jquery-ui/external/jquery/jquery.js')}}"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <!-- Bootstrap JS -->
    <script src="{{asset('js/bootstrap.min.js')}}" crossorigin="anonymous"></script>


    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="{{asset('css/jquery.mCustomScrollbar.min.css')}}?id={{env('ASSET_FLAG')}}">

    <!-- Font Awesome JS -->
    <script defer src="{{asset('js/solid.js')}}"  crossorigin="anonymous"></script>
    <script defer src="{{asset('js/fontawesome.js')}}" crossorigin="anonymous"></script>


    @yield('extra_header')
</head>
<body style="background-image: radial-gradient(circle, #ffffff, #f8f8fd, #f1f2fc, #e7ecfa, #dde7f9);">
<div class="container bg-secondary" style="background: rgba(165,199,255,0.36)">
    <h1 style="background: #0b51c5; color: white; padding: 50px; font-size: 32px">{{$data['title']}} ({{$data['auto']}})</h1>

    <p style="padding: 20px; color: #00040a; font-size: 22px">Értesítés: <strong class="text-primary">{{$data['ertesites']}}</strong></p>
    <p style="padding: 20px; color: #00040a; font-size: 22px"><i class="fas fa-car"></i> Jármű: <strong class="text-primary">{{$data['auto']}}</strong></p>
    <p style="padding: 20px; color: #00040a; font-size: 22px"><i class="fas fa-road"></i>  Km óra: <strong class="text-primary">{{$data['km_ora']}}</strong></p>
</div>

<!-- jQuery Custom Scroller CDN -->
<script src="{{asset('js/jquery.mCustomScrollbar.concat.min.js')}}?id={{env('ASSET_FLAG')}}"></script>

<!-- Bootstrap tooltips -->
<script type="text/javascript" src="{{asset('js/popper.min.js')}}?id={{env('ASSET_FLAG')}}"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}?id={{env('ASSET_FLAG')}}"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="{{asset('js/mdb.min.js')}}?id={{env('ASSET_FLAG')}}"></script>
<!-- Scripts -->

@yield('extra_js')

</body>
</html>
