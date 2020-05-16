
<!doctype html>
<html lang="HU">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>VFleet</title>
    <style>
        .card {
            border: 1px solid #dadada;
            box-shadow: 4px 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.2s;

            border-radius: 5px;
            color: #007bff;
            background-color: white
        }


        .card h3 {
            padding: 2px;
            margin: 8px 0;
            color:#007bff;
            /*   line-height: 20px !important;
              font-size: 18px !important;
              font-weight: 500 !important; */
        }

        .card h1{
            background-color: #007bff;
            border: 1px solid #007bff;
            padding: 5px 15px 5px 15px;
            border-radius: 30px;
            color:white;
        }

        .card:hover {
            box-shadow: 8px 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .card .container {
            padding: 2px 14px;
        }

        .card p {
            margin: 14px 0;
            color:#007bff;
        }


    </style>

</head>
<body style="background-image: radial-gradient(circle, #ffffff, #f8f8fd, #f1f2fc, #e7ecfa, #dde7f9);">
<div class="card">
    <div class="container">
        <h1 class="card"><strong>{{$data['title']}} {{$data['ertesites']}}</strong></h1>
        <h3>{{$data['auto']}}</h3>
        <p><strong>Rendszám: </strong> {{$data['rendszam']}}</p>
        <p><strong>Km óra: </strong> {{$data['km_ora']}}</p>
        <p><strong>Következő várható szerviz: </strong> {{$data['kovetkezo']}} Km óra állásnál.</p>
    </div>
</div>

</body>
</html>
