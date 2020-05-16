

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
            background-color: #d3eaff;
            border: solid 1px #d3eaff;
            color: #007bff;
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
        h1{
            color: #0b51c5;
        }


    </style>

</head>
<body style="background-image: radial-gradient(circle, #ffffff, #f8f8fd, #f1f2fc, #e7ecfa, #dde7f9);">
<h1>Értesítés dokumentum lejártáról. <strong style="color: darkred">A "KGFB (kötelező gépjármű biztosítás)" dokumentum érvényessége 45 nap múlva lejár a HON-154 rendszámú járműnél!</strong></h1>
<table class="zui-table">
    <thead>
    <tr>
        <th>Dokumentum</th>
        <th>Tól</th>
        <th>Ig</th>
        <th>Jármű</th>
        <th>Ár</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>KGFB (kötelező gépjármű biztosítás)</td>
        <td>2019-01-01</td>
        <td>2020-01-01</td>
        <td>Renault Thalia 1.4 RN (HON-154)</td>
        <td>35 000 Ft</td>
    </tr>

    </tbody>
</table>
</body>
</html>
