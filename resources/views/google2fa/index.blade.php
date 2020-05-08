

@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="card" style=" position: absolute;
    width: 40%;
    top: 20%;
    left: 30%;"   id="loginform"  >

            <h5 class="card-header text-white blue-gradient text-center py-4 mb-3">
                2. Faktor a maximális biztonságért
            </h5>

            <!--Card content-->
            <div class="card-body px-lg-5" style="padding-top: 20%; background-image: url(images/vfleetdark.png); background-repeat: no-repeat;
background-size: 80px; background-position-x: center">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ $errors->first() }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
            @endif
            <!-- Form -->
                    <form  method="POST" action="{{ route('2fa') }}">
                        {{ csrf_field() }}


                            @if (!$errors->isEmpty())
                                <div class="col-12 mb-2">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong> {{ config('google2fa.error_messages.wrong_otp') }}</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            @endif


                            <div class="md-form">
                                <input type="number" id="one_time_password" value="{{old('one_time_password')}}"  name="one_time_password" class="form-control {{ !$errors->isEmpty() ? ' has-error-input' : '' }}">
                                <label for="password" onclick="$(this).toggleClass('active')" >Ellenörző Kód</label>
                                @if ($errors->has('password'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                        </div>

                        <button class="btn blue-gradient  btn-block my-4 waves-effect z-depth-0" style="border-radius: 30px" type="submit">Megerősítés</button>

                    </form>
                <!-- Form -->

            </div>

        </div>

    </div>

@endsection

@section('extra_js')
    <script src="{{asset('js/jquery-3.4.1.min.js')}}" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('body').addClass('bg-primary');
            $('html').addClass('bg-primary');
            $('.alert-danger').fadeOut(2000,function () {
                location.href = '{{route('home')}}';
                $('#one_time_password').removeClass('has-error-input');
            });
        });
    </script>
@endsection
