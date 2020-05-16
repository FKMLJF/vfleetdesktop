@extends('layouts.app')

@section('extra_header')
    {{--DATABLE ASSETS--}}

    <link href="{{asset('css/DataTable/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/DataTable/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/DataTable/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/DataTable/buttons.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/DataTable/buttons.bootstrap4.min.css')}}" rel="stylesheet">

    <script src="{{asset('js/DataTable/jquery.validate.js')}}"></script>
    <script defer src="{{asset('js/DataTable/jquery.dataTables.min.js')}}"></script>

    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
          rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
@endsection

@section('content')

    <div class="modal" tabindex="-1" id="qrmodal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Qrkód lekérés</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p><strong class="text-danger text-monospace" id="user_name"></strong> felhasználóhoz tartozó
                        autentikációs kód.</p>
                    <p>
                        <img style="box-shadow: 3px 9px 13px 0px #f5f5f5;" id="user_qrcode" src="">
                    </p>
                    <small class="text-danger">Soha ne adja ki ezt a kódot idegeneknek!</small>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Bezárás</button>
                </div>
            </div>
        </div>
    </div>

    @if(\Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! \Session::get('success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <nav aria-label="breadcrumb ">
        <ol class="breadcrumb bg-primary text-white">
            <li class="breadcrumb-item "><a class="text-decoration-none lg-text" href="{{route('home')}}">Főoldal</a>
            </li>
            <li class="breadcrumb-item active lg-text" aria-current="page">Felhasználók</li>
        </ol>
    </nav>
    <div class="row p-2">
        <div class="col-12">
            <table class="table table-bordered display dt-responsive nowrap" id="felhasznalok_table"
                   style="width:100%">
                <thead class="blue-gradient text-white">
                <th width="10px"></th>
                <th>Azonosító</th>
                <th>Név</th>
                <th>Email</th>
                <th>Szerepkör</th>
                <th>Megerősítve</th>
                <th>Tiltott</th>
                </thead>
                <tbody>
                </tbody>
                <tfoot class="blue-gradient text-white">
                <th width="10px"></th>
                <th>Azonosító</th>
                <th>Név</th>
                <th>Email</th>
                <th>Szerepkör</th>
                <th>Megerősítve</th>
                <th>Tiltott</th>
                </tfoot>
            </table>
        </div>
    </div>

@endsection


@section('extra_js')

    <script type="text/javascript">
        var azonosito = 0;
        var tabnev = null;
        $(document).ready(function () {
            $('.alert-success').fadeOut(3000);

            $(function () {
                var table = $('#felhasznalok_table').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    statesave: true,
                    ajax: {
                        "url": '{{route('felhasznalok.indexdata')}}',
                        "dataType": "json",
                        "type": "get",
                        "data": {_token: "{{csrf_token()}}"}
                    },
                    responsive: true,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Összes"]],
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, visible: false},
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'szerepkor', name: 'szerepkor'},
                        {data: 'megerositve', name: 'megerositve'},
                        {data: 'tiltott', name: 'tiltott'},

                    ],
                    "columnDefs": [
                        {
                            "render": function (data, type, row) {

                                if (data == "1") {
                                    return '<input type="checkbox" checked data-id="' + row.id + '" data-event="megerositett"  class="toggle-btn">'
                                } else {
                                    return '<input type="checkbox" data-id="' + row.id + '" data-event="megerositett"  class="toggle-btn">'
                                }

                            },
                            "targets": 5
                        },
                        {
                            "render": function (data, type, row) {

                                if (data == "1") {
                                    return '<input type="checkbox" checked data-id="' + row.id + '" data-event="tiltott" class="toggle-btn">'
                                } else {
                                    return '<input type="checkbox" data-id="' + row.id + '" data-event="tiltott"   class="toggle-btn">'
                                }

                            },
                            "targets": 6
                        },
                    ],
                    "drawCallback": function (settings) {
                        $('.dtbtn').attr('aria-disabled', true);
                        $('.dtbtn').addClass('disabled');
                        $('.dtbtn').css('cursor', 'not-allowed');

                        $('.toggle-btn').bootstrapToggle({
                            on: 'Igen',
                            off: 'Nem',
                            onstyle: 'success',
                            offstyle: 'danger',
                            size: "mini",
                            width: 75
                        });

                        $('.toggle-btn').change(function () {
                            //$(this).prop('checked')
                            $.ajax({
                                url: "{{route('felhasznalok.userstatuschange')}}",
                                method: 'POST',
                                data: {
                                    _token: "{{csrf_token()}}",
                                    id: $(this).data('id'),
                                    eventtype: $(this).data('event'),
                                    checked: $(this).prop('checked')
                                },
                                context: document.body
                            }).done(function (data) {

                            });


                        })
                    },
                    dom: 'Blfrtip',
                    "language": {
                        url: '{{asset('language.json')}}'
                    },
                    buttons: [
                        {
                            text: '<i class="fa fa-spinner text-white" style="font-size: 16px" aria-hidden="true" title="Táblázat frissítése"></i>',
                            className: 'blue-gradient waves-effect',
                            action: function (e, dt, node, config) {
                                dt.ajax.reload();
                            }
                        },
                        {
                            text: '<i class="fa fa-plus text-white" style="font-size: 16px" title="Új felhasználó"></i>',
                            className: 'blue-gradient waves-effect',
                            action: function (e, dt, node, config) {
                                document.location.href = '{{route('felhasznalok.register')}}';
                            }
                        },
                        {
                            text: '<i class="fa fa-edit text-white" style="font-size: 16px" aria-hidden="true" title="Felhasználó módosítása"></i>',
                            className: 'dtbtn blue-gradient waves-effect',
                            action: function () {
                                if (azonosito == null) return -1;
                                document.location.href = "{{url('/felhasznalok/szerkesztes/')}}/" + azonosito;
                            }
                        },
                        {
                            text: '<i class="fa fa-key text-white" style="font-size: 16px" title="Új jelszó"></i>',
                            className: 'dtbtn blue-gradient waves-effect',
                            action: function (e, dt, node, config) {
                                if (azonosito == null) return -1;
                                document.location.href = "{{url('/felhasznalok/ujjelszo/')}}/" + azonosito;
                            }
                        },
                        {
                            text: '<i class="fa fa-qrcode text-white" style="font-size: 16px" title="QR kód lekérése"></i>',
                            className: 'dtbtn blue-gradient waves-effect',
                            action: function () {
                                $.ajax({
                                    url: "{{route('felhasznalok.getqrcode')}}",
                                    method: 'POST',
                                    data: {_token: "{{csrf_token()}}", id: azonosito},
                                    context: document.body
                                }).done(function (d) {
                                    let data = JSON.parse(d);
                                    console.log(data.qr);
                                    $('#qrmodal').modal('show');
                                    $('#user_qrcode').attr('src', data.qr);
                                    $('#user_name').text(data.name);
                                });
                            }
                        },

                    ]
                });
                $('#felhasznalok_table tbody').on('click', 'tr', function () {
                    $('tr').removeClass('bg-info text-white');
                    $(this).addClass('bg-info text-white');

                    $('.dtbtn').attr('aria-disabled', false);
                    $('.dtbtn').removeClass('disabled');
                    $('.dtbtn').css('cursor', 'pointer');


                    azonosito = table.row(this).data().id;
                });
            });


        });


    </script>


    {{-- Ez valami sátán, megöli a a dropdown menut bs4-ben--}}
    <!-- <script defer  src="{{asset('js/DataTable/bootstrap.min.js')}}"></script> -->
    <script defer src="{{asset('js/DataTable/dataTables.bootstrap4.min.js')}}"></script>
    <script defer src="{{asset('js/DataTable/dataTables.responsive.min.js')}}"></script>
    <script defer src="{{asset('js/DataTable/dataTables.buttons.min.js')}}"></script>
    <script defer src="{{asset('js/DataTable/responsive.bootstrap4.min.js')}}"></script>
    <script defer src="{{asset('js/DataTable/buttons.bootstrap4.min.js')}}"></script>

@endsection
