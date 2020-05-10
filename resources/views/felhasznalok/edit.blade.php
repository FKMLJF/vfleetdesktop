@extends('layouts.app')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-primary float-left" style="width: 100%; border-radius: 0px">
            <li class="breadcrumb-item "><a class="text-decoration-none text-white lg-text" href="{{route('home')}}">Főoldal</a></li>
            <li class="breadcrumb-item active lg-text" aria-current="page"><a class="text-decoration-none text-white lg-text" href="{{route('felhasznalok.index')}}">Felhasználók</a></li>
            <li class="breadcrumb-item active lg-text text-white" aria-current="page">Felhasználó adatok szerkesztése</li>
        </ol>
    </nav>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header blue-gradient"><strong class="text-white text-monospace">{{$user->name}}</strong> felhasználó adatai</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <input type="hidden" name="azonosito" value="{{$user->id}}">
                            <input type="hidden" name="edit" value="1">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Felhasználónév</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ (!empty(old('nev'))?old('nev'): $user->name) }}" required autocomplete="name" autofocus>

                                    @error('name')
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

                                <span  class="offset-4 col-md-6 form-text text-danger">E-mail cím megváltoztatásakor a Qr kód is megváltozik, kérjük módosítás után olvassa be az új kódot telefonjával!</span>

                            </div>

                            <div class="form-group row">
                                <label for="szerepkor_id" class="col-md-4 col-form-label text-md-right">Szerepkör</label>
                                <div class="col-md-6">
                                    <select id="szerepkor_id" name="szerepkor_id" aria-describedby="szerepkor_idgHelpBlock" required="required"
                                            class="custom-select selectpicker {{ $errors->has('szerepkor_id') ? ' is-invalid' : '' }}">
                                        <option value="-1">Válasszon a listából..</option>
                                        @foreach($select as $item)
                                            @if(!empty($role))
                                                @if($role == $item['azonosito'])
                                                    <option value="{{$item['azonosito']}}" selected>{{$item['nev']}}
                                                                                                           </option>
                                                    @else
                                                    <option value="{{$item['azonosito']}}">{{$item['nev']}}
                                                    </option>
                                                    @endif
                                            @else
                                                <option value="{{$item['azonosito']}}">{{$item['nev']}}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span id="_egysegHelpBlock" class="form-text text-muted">Felhasználó szerepköre</span>
                                </div>
                                @if ($errors->has('szerepkor_id'))
                                    <div class="col-12 alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Hiba!</strong> {{ $errors->first('szerepkor_id') }}

                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
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
