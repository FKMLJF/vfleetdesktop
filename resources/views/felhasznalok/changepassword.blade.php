@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item "><a class="text-decoration-none text-success lg-text" href="{{route('home')}}">Főoldal</a></li>
                <li class="breadcrumb-item active lg-text" aria-current="page"><a class="text-decoration-none text-warning lg-text" href="{{route('felhasznalok.index')}}">Felhasználók</a></li>
                <li class="breadcrumb-item active lg-text" aria-current="page">Új jelszó beállítása</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><strong class="text-danger text-monospace">{{$user->name}}</strong> felhasználóhoz új jelszó beállítása</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group row">
                                <input type="hidden" name="azonosito" value="{{$user->id}}">
                                <input type="hidden" name="changepassword" value="1">
                                <label for="jelszo" class="col-md-4 col-form-label text-md-right">Jelszó</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password    " required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="jelszo-confirm" class="col-md-4 col-form-label text-md-right">Jelszó megerősítés</label>

                                <div class="col-md-6">
                                    <input id="jelszo-confirm" type="password" class="form-control" name="jelszo_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Új jelszó beállítása
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
