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
    <div class="row">
        <div class="col-12">
            <table class="table table-secondary table-bordered display dt-responsive nowrap" id="munkalapok_table"
                   style="width:100%">
                <thead class="blue-gradient text-white">
                <th width="10px"></th>
                <th>Azonosító</th>
                <th>Név</th>
                <th>Jármű</th>
                <th>N. ár</th>
                <th>Elvégezve</th>
                <th>leirás</th>
                </thead>
                <tbody>
                </tbody>
                <tfoot class="blue-gradient text-white">
                <th width="10px"></th>
                <th>Azonosító</th>
                <th>Név</th>
                <th>Jármű</th>
                <th>N. ár</th>
                <th>Elvégezve</th>
                <th>leirás</th>
                </tfoot>
            </table>
        </div>

    </div>


@endsection


@section('extra_js')

    <script type="text/javascript">
        $(document).ready(function () {

            var azonosito = null;
            var tabnev = null;
            $(function () {
                var table = $('#munkalapok_table').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    statesave: true,
                    ajax: {
                        "url": '{{route('munkalapok.indexdata')}}',
                        "dataType": "json",
                        "type": "get",
                        "data": {_token: "{{csrf_token()}}"}
                    },
                    responsive: true,
                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Összes"]],
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, visible: false},
                        {data: 'azonosito', name: 'azonosito'},
                        {data: 'nev', name: 'nev'},
                        {data: 'auto_azonosito', name: 'auto_azonosito'},
                        {data: 'ar', name: 'ar'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'leiras', name: 'leiras'},
                    ],
                    "columnDefs": [
                        {
                            "render": function (data, type, row) {
                                return data.substring(0,10);

                            },
                            "targets": 5
                        }
                    ],
                    "drawCallback": function (settings) {
                        $('.dtbtn').attr('aria-disabled', true);
                        $('.dtbtn').addClass('disabled');
                        $('.dtbtn').css('cursor', 'not-allowed');


                    },
                    dom: 'Blfrtip',
                    "language": {
                        url: '{{asset('language.json')}}'
                    },
                    buttons: [
                        {
                            text: '<i class="fa fa-plus text-white" style="font-size: 16px" title="Új mukalap hozzáadása."></i>',
                            className: ' blue-gradient waves-effect',
                            action: function (e, dt, node, config) {
                                document.location.href = '{{route('munkalapok.create')}}';
                            }
                        },
                        {
                            text: '<i class="fa fa-edit text-white" style="font-size: 16px" aria-hidden="true" title="Munkalap módosítása"></i>',
                            className: 'modositas-btn blue-gradient waves-effect',
                            action: function () {
                                if(azonosito == null) return -1;
                                document.location.href = "{{url('/munkalapok/szerkesztes/')}}/"+azonosito;
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
                $('#munkalapok_table tbody').on('click', 'tr', function () {
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
