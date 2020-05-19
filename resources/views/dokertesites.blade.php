

<!doctype html>
<html lang="HU">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>VFleet</title>
    <style>
        .zui-table {
            border: solid 1px #d3eaff;
            border-collapse: collapse;
            border-spacing: 0;
            font: normal 13px Arial, sans-serif;
        }
        .zui-table thead th {
            border: solid 1px #d3eaff;
            color: white;
            padding: 10px;
            text-align: left;
            text-shadow: 1px 1px 1px #fff;
        }
        .zui-table tbody td {
            border: solid 1px #d3eaff;
            color: #333;
            padding: 10px;
            text-shadow: 1px 1px 1px #fff;
        }
        h3{
            color: #0b51c5;
            width: 100%;
        }

        .bg{
            color: white;
            background: #007bff;
        }

        .text-danger{
            color: red;
        }

    </style>

</head>
<body >
@if(!empty($data['ig']))
    <h3>Értesítés dokumentum lejártáról.</h3>
@else
    <h3>Értesítés tartozék lejártáról.</h3>
@endif

<span> A <strong class="text-danger">"{{$data['tipus']}}"</strong>  érvényessége  <strong class="text-danger">{{$data['nap']}}</strong> nap múlva lejár a <strong class="text-danger">{{$data['auto']}}</strong>  járműnél!</span>
<table class="zui-table">
    <thead>
    <tr class="bg">
        @if(!empty($data['ig']))
        <th width="250">Dokumentum</th>
        @else
            <th width="250">Tartozék neve</th>
        @endif
       @if(!empty($data['ig']))
        <th width="80">Tól</th>
        <th width="80">Ig</th>
        @else
            <th width="80">Lejárat</th>
        @endif
        <th width="250">Jármű</th>
        @if(!empty($data['ar']))
        <th width="100">Ár</th>
        @endif
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{$data['tipus']}}</td>
        @if(!empty($data['ig']))
            <td>{{substr($data['tol'],0,10)}}</td>
            <td>{{substr($data['ig'],0,10)}}</td>
        @else
            <td>{{substr($data['tol'],0,10)}}</td>
        @endif
        <td>{{$data['auto']}}</td>
        @if(!empty($data['ar']))
        <td>{{$data['ar']}} Ft</td>
        @endif
    </tr>

    </tbody>
</table>
</body>
</html>
