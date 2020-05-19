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
            <li class="breadcrumb-item"><a class="text-decoration-none text-white lg-text" href="{{route('autok.index')}}">Járművek</a></li>
            <li class="breadcrumb-item active lg-text text-white"
                aria-current="page">{!! (!empty($model)?'Módosítás: <strong class="text-danger">' . $model['rendszam'] . '</strong>' :'Létrehozás')!!}</li>
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

        <div class="card-header blue-gradient text-white">Járműlétrehozás</div>
        <!--Card content-->
        <div class="card-body px-lg-5 pt-0">
            @if ($errors->has('licensz'))
                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Hiba!</strong> {{ $errors->first('licensz') }}

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
        @endif
            <!-- Form -->
            <form class="text-center" style="color: #757575;" action="{{route('autok.store')}}" method="POST">
                @if(!empty($model))
                    <input type="hidden" value="modositas" name="store_method">
                    <input type="hidden" value="{{$model['azonosito']}}" name="azonosito">
                    @else
                    <input type="hidden" value="mentes" name="store_method">
                    @endif
            @csrf
                <div class="form-row">
                    <div class="col-4">

                        <div class="md-form">
                            <input type="text" id="rendszam" name="rendszam"  required class="form-control"     @if(empty($model))
                            value="{{ old('rendszam') }}"
                                   @else
                                   value="{{ $model['rendszam'] }}"
                                @endif>
                            <label for="rendszam" class="active"><strong class="text-danger">*</strong> Rendszám</label>
                            @if ($errors->has('rendszam'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('rendszam') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <!-- Last name -->
                        <div class="md-form">
                            <select id="uzemmod" required name="uzemmod" onchange="onchangeselect('uzemmod')" class="form-control select">
                                <option value="-1"></option>
                                <option value="Benzin"
                               @if(empty($model))
                                {!! old('uzemmod')=='Benzin'?'selected':'' !!}
                                       @else
                                    {!! $model['uzemmod']=='Benzin'?'selected':'' !!}
                                    @endif>Benzin</option>
                                <option value="Dízel"  @if(empty($model))
                                    {!! old('uzemmod')=='Dízel'?'selected':'' !!}
                                    @else
                                    {!! $model['uzemmod']=='Dízel'?'selected':'' !!}
                                    @endif>Dízel</option>
                                <option value="Hibrid Benzin"  @if(empty($model))
                                    {!! old('uzemmod')=='Hibrid Benzin'?'selected':'' !!}
                                    @else
                                    {!! $model['uzemmod']=='Hibrid Benzin'?'selected':'' !!}
                                    @endif>Hibrid Benzin</option>
                                <option value="Hibrid Dízel"  @if(empty($model))
                                    {!! old('uzemmod')=='Hibrid Dízel'?'selected':'' !!}
                                    @else
                                    {!! $model['uzemmod']=='Hibrid Dízel'?'selected':'' !!}
                                    @endif>Hibrid Dízel</option>
                            </select>
                            <label for="uzemmod"  class="    {!! !empty($model['uzemmod'])?'active':'' !!}"><strong class="text-danger">*</strong> Üzemmód</label>
                            @if ($errors->has('uzemmod'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('uzemmod') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">

                        <div class="md-form">
                            <input type="text" id="marka" name="marka" class="form-control"   @if(empty($model))
                            value="{{ old('marka') }}"
                                   @else
                                   value="{{ $model['marka'] }}"
                                @endif>
                            <label for="marka" class="active">Márka</label>
                            @if ($errors->has('marka'))
                                <div class=" alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hiba!</strong> {{ $errors->first('marka') }}

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <!-- Last name -->
                        <div class="md-form">
                            <input type="text" id="tipus" name="tipus" class="form-control"   @if(empty($model))
                            value="{{ old('tipus') }}"
                                   @else
                                   value="{{ $model['tipus'] }}"
                                @endif>
                            <label for="tipus" class="active">Típus</label>
                            @error('tipus')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">

                        <div class="md-form">
                            <input type="text" id="hengerurtartalom" name="hengerurtartalom" class="form-control"   @if(empty($model))
                            value="{{ old('hengerurtartalom') }}"
                                   @else
                                   value="{{ $model['hengerurtartalom'] }}"
                                @endif>
                            <label for="hengerurtartalom" class="active">Hengerürtartalom</label>
                            @error('hengerurtartalom')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <!-- Last name -->
                        <div class="md-form">
                            <input type="text" id="teljesitmeny" name="teljesitmeny" class="form-control"   @if(empty($model))
                            value="{{ old('teljesitmeny') }}"
                                   @else
                                   value="{{ $model['teljesitmeny'] }}"
                                @endif>
                            <label for="teljesitmeny" class="active">Teljesítmény</label>
                            @error('teljesitmeny')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">

                        <div class="md-form">
                            <input type="text" id="tomeg" name="tomeg" class="form-control"   @if(empty($model))
                            value="{{ old('tomeg') }}"
                                   @else
                                   value="{{ $model['tomeg'] }}"
                                @endif>
                            <label for="tomeg" class="active">Tömeg</label>
                            @error('tomeg')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <!-- Last name -->
                        <div class="md-form">
                            <input type="text" id="egyuttestomeg" name="egyuttestomeg" class="form-control"   @if(empty($model))
                            value="{{ old('egyuttestomeg') }}"
                                   @else
                                   value="{{ $model['egyuttestomeg'] }}"
                                @endif>
                            <label for="egyuttestomeg" class="active">Együttes tömeg</label>
                            @error('egyuttestomeg')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">

                        <div class="md-form">
                            <input type="text" id="alvazszam" name="alvazszam" class="form-control"   @if(empty($model))
                            value="{{ old('alvazszam') }}"
                                   @else
                                   value="{{ $model['alvazszam'] }}"
                                @endif>
                            <label for="alvazszam" class="active">Alvázszám</label>
                            @error('alvazszam')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <!-- Last name -->
                        <div class="md-form">
                            <input type="text" id="motorszam" name="motorszam" class="form-control"   @if(empty($model))
                            value="{{ old('motorszam') }}"
                                   @else
                                   value="{{ $model['motorszam'] }}"
                                @endif>
                            <label for="motorszam" class="active">Motorszám</label>
                            @error('motorszam')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">

                        <div class="md-form">

                            <select id="forgalomba_helyezes_ev"  name="forgalomba_helyezes_ev" onchange="onchangeselect('forgalomba_helyezes_ev')" class="form-control select">
                                <option value="-1"></option>
                                @foreach($year as $item)
                                    <option value="{{$item}}"
                                    @if(empty($model))
                                        {!! old('forgalomba_helyezes_ev')==$item?'selected':'' !!}
                                        @else
                                        {!! $model['forgalomba_helyezes_ev']==$item?'selected':'' !!}
                                        @endif
                                        >{{$item}}</option>
                                @endforeach
                            </select>
                            <label for="forgalomba_helyzes_ev" class="{!! !empty($model['forgalomba_helyezes_ev'])?'active':'' !!}">Forgalomba helyezés</label>
                            @error('forgalomba_helyezes_ev')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-4">
                        <!-- Last name -->
                        <div class="md-form">
                            <select id="gyartas_ev"  name="gyartas_ev" onchange="onchangeselect('gyartas_ev')" class="form-control select">
                                <option value="-1"></option>
                                @foreach($year as $item)
                                    <option value="{{$item}}"
                                    @if(empty($model))
                                        {!! old('gyartas_ev')==$item?'selected':'' !!}
                                    @else
                                        {!! $model['gyartas_ev']==$item?'selected':'' !!}
                                    @endif
                                   >{{$item}}</option>
                                @endforeach
                            </select>
                            <label for="gyartas_ev" class="{!! !empty($model['gyartas_ev'])?'active':'' !!}">Gyártás év</label>
                            @error('gyartas_ev')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                    </div>
                    <div class="offset-10 col-2">
                        @if(empty($model))
                        <button class="btn btn-success" type="submit">Rögzítés</button>
                        @else

                            <button class="btn btn-info" type="submit">Módosítás</button>
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

