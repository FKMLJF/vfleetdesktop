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

        <div class="col-3 p-4">
            <div class="rounded blue-gradient waves-effect" style="cursor: pointer" onclick="document.location.href='{{route('felhasznalok.index')}}'">
                <div class=" text-center p-2" style="position: relative">
                    <p class="p-2" style="font-size: 25px">Felhasználók</p>
                    <span style="font-size: 20px">3 db</span>
                    {{-- <span id="usercnt"><i class="fas fa-spinner fa-pulse"></i></span> --}}
                    <i class="fas fa-user" style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <div class="col-3 p-4">
            <div class="rounded blue-gradient waves-effect" style="cursor: pointer" >
                <div class=" text-center p-2" style="position: relative">
                    <p class="p-2" style="font-size: 25px">Járművek</p>
                    <span style="font-size: 20px">6 db</span>
                    {{-- <span id="usercnt"><i class="fas fa-spinner fa-pulse"></i></span> --}}
                    <i class="fas fa-car" style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <div class="col-3 p-4">
            <div class="rounded blue-gradient waves-effect" style="cursor: pointer" >
                <div class=" text-center p-2" style="position: relative">
                    <p class="p-2" style="font-size: 25px">Emlékeztetők</p>
                    <span style="font-size: 20px">12 db</span>
                    {{-- <span id="usercnt"><i class="fas fa-spinner fa-pulse"></i></span> --}}
                    <i class="fas fa-bookmark" style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <div class="col-3 p-4">
            <div class="rounded blue-gradient waves-effect" style="cursor: pointer" >
                <div class=" text-center p-2" style="position: relative">
                    <p class="p-2" style="font-size: 25px">Hibák</p>
                    <span style="font-size: 20px">1 db</span>
                    {{-- <span id="usercnt"><i class="fas fa-spinner fa-pulse"></i></span> --}}
                    <i class="fas fa-bug" style="position: absolute;bottom: 20px;right: 20px;font-size: 70px;opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>


</div>



@endsection
@section('extra_js')

@endsection
