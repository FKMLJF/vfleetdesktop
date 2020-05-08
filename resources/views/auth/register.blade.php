@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Regisztráció</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="nev" class="col-md-4 col-form-label text-md-right">Teljes név</label>

                            <div class="col-md-6">
                                <input id="nev" type="text" class="form-control @error('nev') is-invalid @enderror" name="nev" value="{{ old('nev') }}" required autocomplete="name" autofocus>

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
                                <input id="bejelentkezesi_nev" type="text" class="form-control @error('bejelentkezesi_nev') is-invalid @enderror" name="bejelentkezesi_nev" value="{{ old('bejelentkezesi_nev') }}" required autocomplete="name" autofocus>

                                @error('bejelentkezesi_nev')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-mail cím</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="jelszo" class="col-md-4 col-form-label text-md-right">Jelszó</label>

                            <div class="col-md-6">
                                <input id="jelszo" type="password" class="form-control @error('jelszo') is-invalid @enderror" name="jelszo" required autocomplete="new-password">

                                @error('jelszo')
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
                                    Regisztrálok
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
