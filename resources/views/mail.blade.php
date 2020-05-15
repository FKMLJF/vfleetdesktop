
<!doctype html>
<html lang="HU">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>VFleet</title>

</head>
<body style="background-image: radial-gradient(circle, #ffffff, #f8f8fd, #f1f2fc, #e7ecfa, #dde7f9);">
<div class="container bg-secondary" style="background: rgb(180,210,255); padding: 20px">
    <h1 style="background: #0b51c5; color: white; padding: 50px; font-size: 32px">{{$data['title']}} ({{$data['auto']}})</h1>

    <p style="padding: 20px; color: #00040a; font-size: 22px">Értesítés: <strong class="text-primary">{{$data['ertesites']}}</strong></p>
    <p style="padding: 20px; color: #00040a; font-size: 22px"><i class="fas fa-car"></i> Jármű: <strong class="text-primary">{{$data['auto']}}</strong></p>
    <p style="padding: 20px; color: #00040a; font-size: 22px"><i class="fas fa-road"></i>  Aktuális km óra: <strong class="text-primary">{{$data['km_ora']}}</strong></p>
</div>


</body>
</html>
