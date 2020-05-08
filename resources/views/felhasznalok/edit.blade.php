@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a class="text-decoration-none text-success lg-text" href="{{route('home')}}">Főoldal</a></li>
                <li class="breadcrumb-item active lg-text" aria-current="page"><a class="text-decoration-none text-warning lg-text" href="{{route('felhasznalok.index')}}">Felhasználók</a></li>
                <li class="breadcrumb-item active lg-text" aria-current="page">Felhasználó adatok szerkesztése</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><strong class="text-danger text-monospace">{{$user->bejelentkezesi_nev}}</strong> felhasználó adatai</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <input type="hidden" name="azonosito" value="{{$user->azonosito}}">
                            <input type="hidden" name="edit" value="1">
                            <div class="form-group row">
                                <label for="nev" class="col-md-4 col-form-label text-md-right">Teljes név</label>

                                <div class="col-md-6">
                                    <input id="nev" type="text" class="form-control @error('nev') is-invalid @enderror" name="nev" value="{{ (!empty(old('nev'))?old('nev'): $user->nev) }}" required autocomplete="name" autofocus>

                                    @error('nev')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="bejelentkezesi_nev" class="col-md-4 col-form-label text-md-right">Bejelentkezési név</label>

                                <div class="col-md-6">
                                    <input id="bejelentkezesi_nev" type="text" class="form-control @error('bejelentkezesi_nev') is-invalid @enderror" name="bejelentkezesi_nev" value="{{ (!empty(old('bejelentkezesi_nev'))?old('bejelentkezesi_nev'): $user->bejelentkezesi_nev) }}" required autocomplete="name" autofocus>

                                    @error('bejelentkezesi_nev')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-mail cím</label>

                                <div class="col-md-6 ">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ (!empty(old('email'))?old('email'): $user->email) }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <span  class="offset-4 col-md-6 form-text text-danger">E-mail cím megváltoztatásakor a Qr kód s megváltozik, kérjük módosítás után olvassa be az új kódot telefonjával!</span>

                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Adatok módosítása
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
