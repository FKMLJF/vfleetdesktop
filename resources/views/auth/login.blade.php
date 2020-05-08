@extends('layouts.app')

@section('extra_header')
    <link href="{{asset('css/bootstrap4-toggle.min.css')}}" rel="stylesheet">

    <script src="{{asset('js/bootstrap4-toggle.min.js')}}" crossorigin="anonymous"></script>
    <style>
        .toggle.ios, .toggle-on.ios, .toggle-off.ios {
            border-radius: 20px;
        }

        .toggle.ios .toggle-handle {
            border-radius: 20px;
        }

        i.menu-icon {
            position: absolute;
            bottom: 8%;
            right: 4%;
            font-size: 600%;
            opacity: 0.3;
        }

        div.logo {
            height: 100%;
            width: auto;
            background-size: contain;
            background-image: url(images/vfleet.png);
            background-repeat: no-repeat;
        }

        #header {
            display: none;
            position: absolute;
            top: 0px;
            height: 50px;
            width: 100%;
            overflow: hidden;
        }

        .toggle-handle {
            background: #4285f4 !important;
        }
    </style>
@endsection
@section('content')
    <div class="container">

        <div class="card" style=" position: absolute;
    width: 40%;
    top: 20%;
    left: 30%;"   id="loginform"  >

            <h5 class="card-header text-white blue-gradient text-center py-4 mb-3">
                Flotta menedzsment egyszerűen
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
                <form class="text-center" style="color: #757575;" action="{{route('login')}}" method="POST">
                @csrf
                <!-- Email -->
                    <div class="md-form">
                        <input type="email" id="email" value="{{old('email')}}" name="email" class="form-control">
                        <label for="email" class="thisislabel">E-mail cím</label>
                        @if ($errors->has('email'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Password -->
                    <div class="md-form">
                        <input type="password" id="password" value="{{old('password')}}"  name="password" class="form-control">
                        <label for="password" >Jelszó</label>
                        @if ($errors->has('password'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex ">
                        <div>
                            <!-- Remember me -->

                            <input type="checkbox" class="toggle-btn" id="remember_me" onclick="setRemember()">
                            <label class="form-check-label" >Jegyezz meg!</label>

                        </div>
                        <!-- <div>

                            <a href="">Forgot password?</a>
                        </div> -->
                    </div>

                    <!-- Sign in button -->
                    <button class="btn blue-gradient  btn-block my-4 waves-effect z-depth-0"  onclick="setRemember()" style="border-radius: 30px" type="submit">Bejelentkezés</button>


                </form>
                <!-- Form -->

            </div>

        </div>

    </div>


@endsection
@section('extra_js')
    <script>
        $(document).ready(function () {
            $('.alert-danger').fadeOut(3000);
            $('.toggle-btn').bootstrapToggle({
                on: 'Igen',
                off: 'Nem',
                onstyle: 'bg-white',
                offstyle: 'bg-white',
                style: "ios",
                size: "small",
            });
            $('body').addClass('bg-primary');
            $('html').addClass('bg-primary');

            if (localStorage.getItem('remember') != null) {
                var tmb = JSON.parse(localStorage.getItem('remember'));
                $('#remember_me').attr('checked', 'checked');
                $('.toggle').removeClass('off');
                $('#remember_me').bootstrapToggle('on');
                $('#email').val(tmb[0]);
                $('#password').val(tmb[1]);
            } else {
                $('#remember_me').removeAttr('checked');
                $('#email').val('');
                $('#password').val('');
            }
            if($('#password').val() != '')$('#password').siblings('label').first().addClass('active');
            if($('#email').val() != '')$('#email').siblings('label').first().addClass('active');
        });

        function setRemember() {
            if ($('#remember_me').is(':checked') && $('#email').val() != '' && $('#password').val() != '') {
                var datas = [ $('#email').val(), $('#password').val()];
                localStorage.setItem('remember', JSON.stringify(datas));
            } else {
                localStorage.removeItem('remember')
            }
        }

    </script>
@endsection
