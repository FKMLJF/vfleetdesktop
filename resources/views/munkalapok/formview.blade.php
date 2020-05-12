@extends('layouts.app')

@section('extra_header')
    <link href="{{asset('css/bootstrap4-toggle.min.css')}}" rel="stylesheet">

    <script src="{{asset('js/app.js')}}" crossorigin="anonymous"></script>
    <script src="{{asset('js/bootstrap4-toggle.min.js')}}" crossorigin="anonymous"></script>
@endsection

@section('content')
    <nav aria-label="breadcrumb text-monospace">
        <ol class="breadcrumb bg-primary">
            <li class="breadcrumb-item "><a class="text-decoration-none text-white lg-text"  href="{{route('home')}}">Főoldal</a></li>
            <li class="breadcrumb-item"><a class="text-decoration-none text-white lg-text" href="{{route('munkalapok.index')}}">Munkalapok</a></li>
            <li class="breadcrumb-item active lg-text text-white"
                aria-current="page">{!! (!empty($model)?'Módosítás: <strong class="text-danger">' . $model['azonosito'] . '</strong>' :'Létrehozás')!!}</li>
        </ol>
    </nav>
    @if(\Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! \Session::get('success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="card m-2 ">

        <div class="card-header blue-gradient text-white">Munkalap rögzítés</div>
        <!--Card content-->
        <div class="card-body px-lg-5 pt-0">

            <!-- Form -->
            <form class="text-center" style="color: #757575;" action="{{route('munkalapok.store')}}" method="POST">
                @if(!empty($model))
                    <input type="hidden" value="modositas" name="store_method">
                    <input type="hidden" value="{{$model['azonosito']}}" name="azonosito">
                    @else
                    <input type="hidden" value="mentes" name="store_method">
                    @endif
            @csrf
                <div class="form-row">
                    <div class="col-3">
                        <!-- First name -->
                        <div class="md-form">
                            <input type="text" id="nev" name="nev"  required class="form-control"     @if(empty($model))
                            value="{{ old('nev') }}"
                                   @else
                                   value="{{ $model['nev'] }}"
                                @endif>
                            <label for="rendszam" class="active"><strong class="text-danger">*</strong> Rövid leírás</label>
                            @if ($errors->has('nev'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('nev') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-3">
                        <!-- First name -->
                        <div class="md-form">
                            <input type="text" id="ar" name="ar"  required class="form-control"     @if(empty($model))
                            value="{{ old('ar') }}"
                                   @else
                                   value="{{ $model['ar'] }}"
                                @endif>
                            <label for="rendszam" class="active"><strong class="text-danger">*</strong>Nettó Ár</label>
                            @if ($errors->has('ar'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('ar') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-3">

                        <div class="md-form">

                            <select id="auto_azonosito"  name="auto_azonosito" onchange="onchangeselect('auto_azonosito')" class="form-control select">
                                <option value="-1"></option>
                                @foreach($select as $item)
                                    <option value="{{$item['azonosito']}}"
                                    @if(empty($model))
                                        {!! old('auto_azonosito')==$item['azonosito']?'selected':'' !!}
                                        @else
                                        {!! $model['auto_azonosito']==$item['azonosito']?'selected':'' !!}
                                        @endif
                                    >{{$item['marka']}} {{$item['tipus']}} ({{$item['rendszam']}})</option>
                                @endforeach
                            </select>
                            <label for="auto_azonosito" class="{!! !empty($model['auto_azonosito'])?'active':'' !!}"><strong class="text-danger">*</strong> Jármű</label>
                            @if ($errors->has('auto_azonosito'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('auto_azonosito') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-3">
                        <!-- First name -->
                        <div class="md-form">
                            <input type="date" id="created_at" name="created_at"   class="form-control"     @if(empty($model))
                            value="{{ old('created_at') }}"
                                   @else
                                   value="{{ substr($model['created_at'], 0,10) }}"
                                @endif>
                            <label for="created_at" class="active"> Elvégezve</label>
                            @if ($errors->has('created_at'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('created_at') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <!-- First name -->
                        <div class="md-form">
                            <textarea type="text" id="leiras" name="leiras" rows="8" class="form-control">@if(empty($model)){{ old('leiras')}}@else{{ $model['leiras']}}@endif</textarea>
                            <label for="leiras" class="active active2">Leírás</label>
                            @if ($errors->has('leiras'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('leiras') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="offset-10">
                        @if(empty($model))
                        <button class="btn btn-success btn-block" type="submit">Rögzítés</button>
                        @else

                            <button class="btn btn-info btn-block" type="submit">Módosítás</button>
                        @endif
                    </div>
                </div>


            </form>
            <!-- Form -->

        </div>

    </div>

@endsection


@section('extra_js')
    <script>
        function onchangeselect(elem){
            if($('#'+elem).val()!=-1)
            {
                $('#'+elem).siblings("label").addClass('active');
            }else{
                $('#'+elem).siblings("label").removeClass('active');
            }

        }
        @if(\Session::has('success'))
        $(document).ready(function () {
            $('.alert-success').fadeOut(3000);
        });
        @endif
    </script>
@endsection

