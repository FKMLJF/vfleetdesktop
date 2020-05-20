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
            <li class="breadcrumb-item"><a class="text-decoration-none text-white lg-text" href="{{route('tartozek.index')}}">Autó tartzozékok</a></li>
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

        <div class="card-header blue-gradient text-white">Tartozékok rögzítés</div>
        <!--Card content-->
        <div class="card-body px-lg-5 pt-0">

            <!-- Form -->
            <form class="text-center" style="color: #757575;" action="{{route('tartozek.store')}}" method="POST">
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

                            <select id="auto_azonosito"  name="auto_azonosito" onchange="onchangeselect('auto_azonosito')" class="form-control select">
                                @if(!empty($model))
                                    @foreach($select as $item)
                                        @if($model['auto_azonosito']==$item['azonosito'])
                                            <option value="{{$item['azonosito']}}"  >{{$item['marka']}} {{$item['tipus']}} ({{$item['rendszam']}})</option>
                                            @endif
                                    @endforeach
                                    @else
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
                                @endif


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
                            <input type="text" id="tartozek_neve" name="tartozek_neve"  class="form-control"     @if(empty($model))
                            value="{{ old('tartozek_neve') }}"
                                   @else
                                   value="{{ $model['tartozek_neve'] }}"
                                @endif>
                            <label for="tartozek_neve" class="active"><strong class="text-danger">*</strong> Tartozék neve <i class="km pl-2 pt-2"></i></label>
                            @if ($errors->has('tartozek_neve'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('tartozek_neve') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-3">

                        <div class="md-form">
                            <input type="number" id="mennyiseg" name="mennyiseg" required  class="form-control"     @if(empty($model))
                            value="{{ old('mennyiseg') }}"
                                   @else
                                   value="{{ substr($model['mennyiseg'], 0,10) }}"
                                @endif>
                            <label for="mennyiseg" class="active"><strong class="text-danger">*</strong> Mennyiség</label>
                            @if ($errors->has('mennyiseg'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('mennyiseg') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-3">

                        <div class="md-form">
                            <input type="date" id="lejarat" name="lejarat"   class="form-control"     @if(empty($model))
                            value="{{ old('lejarat') }}"
                                   @else
                                   value="{{ substr($model['lejarat'], 0,10) }}"
                                @endif>
                            <label for="lejarat" class="active"> Lejárat</label>
                            @if ($errors->has('lejarat'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('lejarat') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="md-form">
                            <input type="number" id="ertesites_nap" name="ertesites_nap"   class="form-control"     @if(empty($model))
                            value="{{ old('ertesites_nap') }}"
                                   @else
                                   value="{{ $model['ertesites_nap']}}"
                                @endif>
                            <label for="created_at" class="active"> Értesités nap</label>
                            @if ($errors->has('ertesites_nap'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('ertesites_nap') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="md-form">
                            <input type="text" id="cimzettek" name="cimzettek"    class="form-control"     @if(empty($model))
                            value="{{ old('cimzettek') }}"
                                   @else
                                   value="{{ $model['cimzettek'] }}"
                                @endif>
                            <label for="created_at" class="active">Címzettek <strong class="text-danger">(E-mail címek ponto vessővel ( ; ) elválasztva!)</strong></label>
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
                    <div class="col-12">

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

