@extends('layouts.app')

@section('extra_header')

    {{--DATABLE ASSETS--}}
    <link href="{{asset('css/DataTable/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/DataTable/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/DataTable/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/DataTable/buttons.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/DataTable/buttons.bootstrap4.min.css')}}" rel="stylesheet">

    <script src="{{asset('js/DataTable/jquery.validate.js')}}"></script>
    <script defer  src="{{asset('js/DataTable/jquery.dataTables.min.js')}}"></script>


    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
          rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
@endsection

@section('content')
    <nav aria-label="breadcrumb ">
        <ol class="breadcrumb bg-primary">
            <li class="breadcrumb-item "><a class="text-decoration-none text-white lg-text" href="{{route('home')}}">Főoldal</a></li>
            <li class="breadcrumb-item active lg-text text-white" aria-current="page">Járművek</li>
        </ol>
    </nav>

        <div class=" alert alert-danger alert-dismissible error-alert" style="display: none" role="alert">
            <strong>Hiba!</strong> <span id="error-text"></span>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

    <div class="row">
        <div class="col-12">
            <table class="table table-secondary table-bordered display dt-responsive nowrap" id="autok_table"
                   style="width:100%">
                <thead class="blue-gradient text-white">
                <th width="10px"></th>
                <th>Azonosító</th>
                <th>Rendszám</th>
                <th>Márka</th>
                <th>Típus</th>
                <th>Rejtett</th>
                </thead>
                <tbody>
                </tbody>
                <tfoot class="blue-gradient text-white">
                <th width="10px"></th>
                <th>Azonosító</th>
                <th>Rendszám</th>
                <th>Márka</th>
                <th>Típus</th>
                <th>Rejtett</th>
                </tfoot>
            </table>
        </div>

    </div>
    @if(!empty($licenscnt))
        <i  style="position: absolute; bottom: 20px; right: 20px">Jármű licensz: <strong id="licensevalue">{{$licenscnt->jarmu}}</strong> db</i>
    @endif

@endsection


@section('extra_js')

    <script type="text/javascript">
        $(document).ready(function () {

            var azonosito = null;
            var tabnev = null;
            $(function () {
                var table = $('#autok_table').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    statesave: true,
                    ajax: {
                        "url": '{{route('autok.indexdata')}}',
                        "dataType": "json",
                        "type": "get",
                        "data": {_token: "{{csrf_token()}}"}
                    },
                    responsive: true,
                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Összes"]],
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, visible: false},
                        {data: 'azonosito', name: 'azonosito'},
                        {data: 'rendszam', name: 'rendszam'},
                        {data: 'marka', name: 'marka'},
                        {data: 'tipus', name: 'tipus'},
                        {data: 'rejtett', name: 'rejtett'},
                    ],
                    "columnDefs": [
                        {
                            "render": function (data, type, row) {

                                if (data == "1") {
                                    return '<input type="checkbox" checked data-id="' + row.azonosito + '"  class="toggle-btn">'
                                } else {
                                    return '<input type="checkbox" data-id="' + row.azonosito + '"  class="toggle-btn">'
                                }

                            },
                            "targets": 5
                        }
                    ],
                    "drawCallback": function (settings) {
                        $('.dtbtn').attr('aria-disabled', true);
                        $('.dtbtn').addClass('disabled');
                        $('.dtbtn').css('cursor', 'not-allowed');

                        $('.toggle-btn').bootstrapToggle({
                            on: 'Igen',
                            off: 'Nem',
                            onstyle: 'danger',
                            offstyle: 'success',
                            size: "mini",
                            width: 75
                        });

                        $('.toggle-btn').change(function () {
                            let btn = $(this);
                            $.ajax({
                                url: "{{route('autok.visible')}}",
                                method: 'POST',
                                data: {
                                    _token: "{{csrf_token()}}",
                                    id: $(this).data('id'),
                                    checked: $(this).prop('checked')
                                },
                                context: document.body,
                                dataType : "json",
                                success: function (data) {
                                    if(!data.license){

                                        btn.bootstrapToggle('on');
                                        $(".error-alert").show();
                                        $(".error-alert").fadeOut(3000);
                                        $("#error-text").text("Elfogyott a licensz! Maximális járműlicensz:"+ $('#licensevalue').text());
                                        return -1;
                                    }
                                }
                            }).done(function (data) {
                                return -1;
                            });


                        })
                    },
                    dom: 'Blfrtip',
                    "language": {
                        url: '{{asset('language.json')}}'
                    },
                    buttons: [
                        {
                            text: '<i class="fa fa-plus text-white" style="font-size: 16px" title="Új jármű hozzáadása."></i>',
                            className: ' blue-gradient waves-effect',
                            action: function (e, dt, node, config) {
                                document.location.href = '{{route('autok.create')}}';
                            }
                        },
                        {
                            text: '<i class="fa fa-edit text-white" style="font-size: 16px" aria-hidden="true" title="Jármű módosítása"></i>',
                            className: 'modositas-btn blue-gradient waves-effect',
                            action: function () {
                                if(azonosito == null) return -1;
                                document.location.href = "{{url('/autok/szerkesztes/')}}/"+azonosito;
                            }
                        },
                        {
                            text: '<i class="fa fa-spinner text-white" style="font-size: 16px" aria-hidden="true" title="Táblázat frissítése"></i>',
                            className: ' blue-gradient waves-effect',
                            action: function (e, dt, node, config) {
                                dt.ajax.reload();
                            }
                        },


                    ]
                });
                $('#autok_table tbody').on('click', 'tr', function () {
                    $('tr').removeClass('bg-info text-white');
                    $(this).addClass('bg-info text-white');
                    $('.modositas-btn').attr('aria-disabled', false);
                    $('.modositas-btn').removeClass('disabled');

                    azonosito = table.row(this).data().azonosito;
                });

                table.on( 'draw', function () {
                    $('.modositas-btn').attr('aria-disabled', true);
                    $('.modositas-btn').addClass('disabled');
                } );
            });




        });



    </script>


    {{-- Ez valami sátán, megöli a a dropdown menut bs4-ben--}}
    <!-- <script defer  src="{{asset('js/DataTable/bootstrap.min.js')}}"></script> -->
    <script defer  src="{{asset('js/DataTable/dataTables.bootstrap4.min.js')}}"></script>
    <script defer  src="{{asset('js/DataTable/dataTables.responsive.min.js')}}"></script>
    <script defer  src="{{asset('js/DataTable/dataTables.buttons.min.js')}}"></script>
    <script defer  src="{{asset('js/DataTable/responsive.bootstrap4.min.js')}}"></script>
    <script defer  src="{{asset('js/DataTable/buttons.bootstrap4.min.js')}}"></script>

@endsection
