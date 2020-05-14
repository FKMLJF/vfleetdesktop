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
            <li class="breadcrumb-item"><a class="text-decoration-none text-white lg-text" href="{{route('ertesitesek.index')}}">Értesítések</a></li>
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

        <div class="card-header blue-gradient text-white">Értesítés rögzítés</div>
        <!--Card content-->
        <div class="card-body px-lg-5 pt-0">

            <!-- Form -->
            <form class="text-center" style="color: #757575;" action="{{route('ertesitesek.store')}}" method="POST">
                @if(!empty($model))
                    <input type="hidden" value="modositas" name="store_method">
                    <input type="hidden" value="{{$model['azonosito']}}" name="azonosito">
                    @else
                    <input type="hidden" value="mentes" name="store_method">
                    @endif
            @csrf
                <div class="form-row">

                    <div class="col-3">

                        <div class="md-form">

                            <select id="auto_azonosito"  name="auto_azonosito" onchange="select('auto_azonosito')" class="form-control select">
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
                        <div class="md-form">
                            <input type="text" id="nev" name="nev"  required  class="form-control"     @if(empty($model))
                            value="{{ old('nev') }}"
                                   @else
                                   value="{{ $model['nev'] }}"
                                @endif>
                            <label for="created_at" class="active"><strong class="text-danger">*</strong> Értesítés neve</label>
                            @if ($errors->has('nev'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('created_at') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="md-form">
                            <input type="number" id="km_ora" name="km_ora" required  class="form-control"     @if(empty($model))
                            value="{{ old('km_ora') }}"
                                   @else
                                   value="{{ $model['km_ora'] }}" readonly
                                @endif>
                            <label for="created_at" class="active"><strong class="text-danger">*</strong>Km óra <i class="km pl-2 pt-2"></i></label>
                            @if ($errors->has('km_ora'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('km_ora') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="md-form">
                            <input type="text" id="gyakorisag" name="gyakorisag" required class="form-control"     @if(empty($model))
                            value="{{ old('gyakorisag') }}"
                                   @else
                                   value="{{ $model['gyakorisag'] }}"
                                @endif>
                            <label for="created_at" class="active"><strong class="text-danger">*</strong> Gyakoriság (Km)</label>
                            @if ($errors->has('gyakorisag'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('gyakorisag') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="md-form">
                            <input type="text" id="cimzettek" name="cimzettek" required   class="form-control"     @if(empty($model))
                            value="{{ old('cimzettek') }}"
                                   @else
                                   value="{{ $model['cimzettek'] }}"
                                @endif>
                            <label for="created_at" class="active"><strong class="text-danger">*</strong> Címzettek <strong class="text-danger">(E-mail címek ponto vessővel ( ; ) elválasztva!)</strong></label>
                            @if ($errors->has('cimzettek'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('cimzettek') }}

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
        function select(elem){
            if($('#'+elem).val()!=-1)
            {
                $('#'+elem).siblings("label").addClass('active');
            }else{
                $('#'+elem).siblings("label").removeClass('active');
            }
            $.ajax({
                url: "{{route('ertesitesek.minkm')}}",
                method: 'POST',
                data: { auto_azonosito: $('#'+elem).val(), _token: "{{csrf_token()}}"},
                dataType: "json",
                success: function (data) {
                   $(".km").text("( Minimum: "+ data.km + " )  ");
                }
            }).done(function (data) {
                console.log(data);
            });
        }

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

