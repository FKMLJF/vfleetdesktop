@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb bg-primary text-white">
            <ol class="breadcrumb bg-primary text-white">
                <li class="breadcrumb-item "><a class="text-decoration-none text-white lg-text"
                                                href="{{route('home')}}">Főoldal</a></li>
                <li class="breadcrumb-item active lg-text" aria-current="page"><a
                        class="text-decoration-none text-white lg-text" href="{{route('felhasznalok.index')}}">Felhasználók</a>
                </li>

            </ol>
        </nav>
        <div class="row justify-content-center">

            <div class="col-md-8">
                <div class="card">
                    @if(\Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {!! \Session::get('error') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="card-header blue-gradient text-white">Felhasználó Létrehozás</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <input type="hidden" name="withoutfa" value="1">
                            <div class="row">
                                <div class="col-6">
                                    <div class="md-form">
                                        <label for="nev">Felhasználónév</label>
                                        <input id="nev" type="text"
                                               class="form-control @error('nev') is-invalid @enderror"
                                               name="nev" value="{{ old('nev') }}" required autocomplete="name"
                                               autofocus>
                                        @error('nev')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="md-form">
                                        <label for="email">E-mail cím</label>
                                        <input id="email" type="email"
                                               class="form-control @error('email') is-invalid @enderror" name="email"
                                               value="{{ old('email') }}" required autocomplete="email">

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="md-form">
                                        <label for="password">Jelszó</label>
                                        <input id="password" type="password"
                                               class="form-control @error('jelszo') is-invalid @enderror" name="password"
                                               required autocomplete="new-password">

                                        @error('jelszo')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="md-form">
                                        <label for="jelszo-confirm">Jelszó
                                            megerősítés</label>

                                        <input id="jelszo-confirm" type="password" class="form-control"
                                               name="jelszo_confirmation" required autocomplete="new-password">

                                    </div>
                                </div>

                                <div class="col-6">
                                    <label for="szerepkor" class="col-4 col-form-label">Szerepkör</label>
                                    <div class="col-8">
                                        <select id="szerepkor" name="szerepkor" aria-describedby="szerepkorHelpBlock" required="required"
                                                class="custom-select selectpicker select {{ $errors->has('_egyseg') ? ' is-invalid' : '' }}">
                                            <option value="-1">Válasszon a listából..</option>
                                            @foreach($select as $item)

                                                <option value="{{$item['azonosito']}}">{{$item['nev']}}

                                                </option>

                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('_egyseg'))
                                        <div class="col-12 alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Hiba!</strong> {{ $errors->first('_egyseg') }}

                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="offset-10 col-2">
                                    <button type="submit" class="btn btn-primary  waves-effect">
                                        Mentés
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
