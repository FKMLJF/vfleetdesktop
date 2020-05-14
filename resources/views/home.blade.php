@extends('layouts.app')
@section('extra_header')

<style>
    .rounded:hover{
        transform: scale(1.02, 1.02);
    }
    .rounded{
        border-radius: 30px !important;
    }

</style>
@endsection
@section('content')
<div class="container-fluid p-4">


    <div class="row text-white">

        @if($role == 3)
        <div class="col-3 p-4">
            <div class="rounded blue-gradient waves-effect" style="cursor: pointer" onclick="document.location.href='{{route('felhasznalok.index')}}'">
                <div class=" text-center p-2" style="position: relative">
                    <p class="p-2" style="font-size: 25px">Felhasználók</p>
                    <span id="usercnt" style="font-size: 20px"><i class="fas fa-spinner fa-pulse"></i></span>
                    <i class="fas fa-user" style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                </div>
            </div>
        </div>
        @endif

        <div class="col-3 p-4">
            <div class="rounded blue-gradient waves-effect" style="cursor: pointer" onclick="document.location.href='{{route('autok.index')}}'">
                <div class=" text-center p-2" style="position: relative">
                    <p class="p-2" style="font-size: 25px">Járművek</p>
                     <span id="carcnt" style="font-size: 20px"><i class="fas fa-spinner fa-pulse"></i></span>
                    <i class="fas fa-car" style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <div class="col-3 p-4">
            <div class="rounded blue-gradient waves-effect" style="cursor: pointer" onclick="document.location.href='{{route('munkalapok.index')}}'">
                <div class=" text-center p-2" style="position: relative">
                    <p class="p-2" style="font-size: 25px">Munkalapok</p>
                     <span id="munkalapcnt" style="font-size: 20px"><i class="fas fa-spinner fa-pulse"></i></span>
                    <i class="fas fa-edit" style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                </div>
            </div>
        </div>


        <div class="col-3 p-4">
            <div class="rounded blue-gradient waves-effect" style="cursor: pointer" onclick="document.location.href='{{route('ertesitesek.index')}}'">
                <div class=" text-center p-2" style="position: relative">
                    <p class="p-2" style="font-size: 25px">Értesítések</p>
                     <span id="ertcnt" style="font-size: 25px"><i class="fas fa-spinner fa-pulse"></i></span>
                    <i class="fas fa-bookmark" style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <div class="col-3 p-4">
            <div class="rounded blue-gradient waves-effect" style="cursor: pointer" onclick="document.location.href='{{route('hibak.index')}}'">
                <div class=" text-center p-2" style="position: relative">
                    <p class="p-2" style="font-size: 25px">Hibák</p>
                    <span id="errorcnt" style="font-size: 20px"><i class="fas fa-spinner fa-pulse"></i></span>
                    <i class="fas fa-bug" style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>


</div>



@endsection
@section('extra_js')
    <script>
        $(document).ready(function () {
            $.ajax({
                url: "{{route('widgetdata')}}",
                method: 'POST',
                data: {_token: "{{csrf_token()}}"},
                context: document.body
            }).done(function (d) {
                let data = JSON.parse(d);
                $('#usercnt').text(data[0]);
                $('#carcnt').text(data[1]);
                $('#munkalapcnt').text(data[2]);
                $('#errorcnt').text(data[3]);
                $('#ertcnt').text(data[4]);
                /*$('#inpcnt').text(data[1]);
                $('#servicecnt').text(data[3]);
                $('#servicetcnt').text(data[4]);
                $('#templatecnt').text(data[5]);
                $('#termcnt').text(data[6]);
                $('#kintlevoseg').text(data[7]);
                $('#fizetetseg').text(data[8]);*/
            });
        });
    </script>

@endsection
