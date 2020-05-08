@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Google Authenticator beállítás</div>

                    <div class="panel-body" style="text-align: center;">
                        <p>Állítsa be a két faktoros hitelesítést az alábbi vonalkód beolvasásával. Alternatív megoldásként használhatja a kódot: {{ $secret }}</p>
                        <div>
                            <img src="{{ $QR_Image }}">
                        </div>
                        @if (!@$reauthenticating) {{-- add this line --}}
                        <p>
                            A folytatás előtt be kell állítania a Google Authenticator alkalmazást. Másként nem tud bejelentkezni</p>
                        <div>
                            <a href="{{route('cregistration')}}"><button class="btn-primary">Regisztráció</button></a>
                        </div>
                        @endif {{-- and this line --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
