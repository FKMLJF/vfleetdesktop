@extends('layouts.app')
@section('extra_header')


@endsection
@section('content')
    <div class="container-fluid p-4">


        <div class="row text-white">

            @if($role == 3)
                <div class="col-3 p-2">
                    <div class="rounded blue-gradient waves-effect" style="cursor: pointer"
                         onclick="document.location.href='{{route('felhasznalok.index')}}'">
                        <div class=" text-center p-2" style="position: relative">
                            <p class="p-2" style="font-size: 25px">Felhasználók</p>
                            <span id="usercnt" style="font-size: 25px"><i class="fas fa-spinner fa-pulse"></i></span>
                            <i class="fas fa-user"
                               style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            @endif
            @if($role == 3)
                <div class="col-3 p-2">
                    <div class="rounded blue-gradient waves-effect" style="cursor: pointer"
                         onclick="document.location.href='{{route('autok.index')}}'">
                        <div class=" text-center p-2" style="position: relative">
                            <p class="p-2" style="font-size: 25px">Járművek</p>
                            <span id="carcnt" style="font-size: 25px"><i class="fas fa-spinner fa-pulse"></i></span>
                            <i class="fas fa-car"
                               style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-3 p-2">
                <div class="rounded blue-gradient waves-effect" style="cursor: pointer"
                     onclick="document.location.href='{{route('munkalapok.index')}}'">
                    <div class=" text-center p-2" style="position: relative">
                        <p class="p-2" style="font-size: 25px">Munkalapok</p>
                        <span id="munkalapcnt" style="font-size: 25px"><i class="fas fa-spinner fa-pulse"></i></span>
                        <i class="fas fa-edit"
                           style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                    </div>
                </div>
            </div>

            @if($role == 3)
                <div class="col-3 p-2">
                    <div class="rounded blue-gradient waves-effect" style="cursor: pointer"
                         onclick="document.location.href='{{route('ertesitesek.index')}}'">
                        <div class=" text-center p-2" style="position: relative">
                            <p class="p-2" style="font-size: 25px">Értesítések</p>
                            <span id="ertcnt" style="font-size: 25px"><i class="fas fa-spinner fa-pulse"></i></span>
                            <i class="fas fa-bookmark"
                               style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            @endif
            @if($role == 3)
                <div class="col-3 p-2">
                    <div class="rounded blue-gradient waves-effect" style="cursor: pointer"
                         onclick="document.location.href='{{route('dokumentumok.index')}}'">
                        <div class=" text-center p-2" style="position: relative">
                            <p class="p-2" style="font-size: 25px">Dokumentumok</p>
                            <span id="doccnt" style="font-size: 25px"><i class="fas fa-spinner fa-pulse"></i></span>
                            <i class="fas fa-file"
                               style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-3 p-2">
                <div class="rounded blue-gradient waves-effect" style="cursor: pointer"
                     onclick="document.location.href='{{route('hibak.index')}}'">
                    <div class=" text-center p-2" style="position: relative">
                        <p class="p-2" style="font-size: 25px">Hibák</p>
                        <span id="errorcnt" style="font-size: 25px"><i class="fas fa-spinner fa-pulse"></i></span>
                        <i class="fas fa-bug"
                           style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
                @if($role == 3)
                    <div class="col-3 p-2">
                        <div class="rounded blue-gradient waves-effect" style="cursor: pointer"
                             onclick="document.location.href='{{route('tankolas.index')}}'">
                            <div class=" text-center p-2" style="position: relative">
                                <p class="p-2" style="font-size: 25px">Tankolások</p>
                                <span id="fuel_cnt" style="font-size: 25px"><i class="fas fa-spinner fa-pulse"></i></span>
                                <i class="fas fa-gas-pump"
                                   style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                @endif

                @if($role == 3)
                    <div class="col-3 p-2">
                        <div class="rounded blue-gradient waves-effect" style="cursor: pointer"
                             onclick="document.location.href='{{route('tartozek.index')}}'">
                            <div class=" text-center p-2" style="position: relative">
                                <p class="p-2" style="font-size: 25px">Autó tartozékok</p>
                                <span id="store_cnt" style="font-size: 25px"><i class="fas fa-spinner fa-pulse"></i></span>
                                <i class="fas fa-cube"
                                   style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                @endif
        </div>


    </div>
    <img class=" waves-effect wfleet-bottom" onclick="  window.history.go(-1); "
         src="{{asset('/images/vfleetdark.png')}}">


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
                $('#doccnt').text(data[5]);
                $('#fuel_cnt').text(data[6]);
                $('#store_cnt').text(data[7]);
            });
        });
    </script>

@endsection
