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
    <link rel="stylesheet" href="{{asset('jquery-ui/jquery-ui.min.css')}}">
@endsection

@section('content')
    <div id="dialog-confirm" style="display: none" title="Biztos törli?" class="blue-gradient">
        <p class="text-white"><span  style="float:left; margin:12px 12px 20px 0;"></span>A törlés nem vonható vissza, folytatja?</p>
    </div>
    <nav aria-label="breadcrumb ">
        <ol class="breadcrumb bg-primary">
            <li class="breadcrumb-item "><a class="text-decoration-none text-white lg-text" href="{{route('home')}}">Főoldal</a></li>
            <li class="breadcrumb-item active lg-text text-white" aria-current="page">Tankolások</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-12">
            <table class="table table-secondary table-bordered display dt-responsive nowrap" id="tankolas_table"
                   style="width:100%">
                <thead class="blue-gradient text-white">
                <th width="10px"></th>
                <th>Azonosító</th>
                <th>Jármű</th>
                <th>Km óra</th>
                <th>Liter</th>
                <th>Összeg</th>
                <th>Felhasználó</th>
                <th>Rögzítve</th>
                </thead>
                <tbody>
                </tbody>
                <tfoot class="blue-gradient text-white">
                <th width="10px"></th>
                <th>Azonosító</th>
                <th>Jármű</th>
                <th>Km óra</th>
                <th>Liter</th>
                <th>Összeg</th>
                <th>Felhasználó</th>
                <th>Rögzítve</th>
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
                var table = $('#tankolas_table').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    statesave: true,
                    ajax: {
                        "url": '{{route('tankolas.indexdata')}}',
                        "dataType": "json",
                        "type": "get",
                        "data": {_token: "{{csrf_token()}}"}
                    },
                    responsive: true,
                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Összes"]],
                    pageLength: 50,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, visible: false},
                        {data: 'azonosito', name: 'azonosito'},
                        {data: 'auto', name: 'auto'},
                        {data: 'km_ora', name: 'km_ora'},
                        {data: 'liter', name: 'liter'},
                        {data: 'osszeg', name: 'osszeg', render: $.fn.dataTable.render.number(' ', '.', 0, '')},
                        {data: 'user_email', name: 'user_email'},
                        {data: 'inserted_at', name: 'inserted_at'},
                    ],
                    "columnDefs": [
                        {
                            "render": function (data, type, row) {
                                return data.substring(0,10);

                            },
                            "targets": 7
                        },
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
                            text: '<i class="fa fa-plus text-white" style="font-size: 16px" title="Új tankolás hozzáadása."></i>',
                            className: ' blue-gradient waves-effect',
                            action: function (e, dt, node, config) {
                                document.location.href = '{{route('tankolas.create')}}';
                            }
                        },
                        {
                            text: '<i class="fa fa-edit text-white" style="font-size: 16px" aria-hidden="true" title="Tankolás módosítása"></i>',
                            className: 'modositas-btn blue-gradient waves-effect',
                            action: function () {
                                if(azonosito == null) return -1;
                                document.location.href = "{{url('/tankolas/szerkesztes/')}}/"+azonosito;
                            }
                        },
                        @if(session('role', 0) == 3)
                        {
                            text: '<i class="fa fa-trash text-white" style="font-size: 16px" aria-hidden="true" title="tankolás törlése"></i>',
                            className: 'modositas-btn blue-gradient waves-effect',
                            action: function () {
                                if(azonosito == null) return -1;
                                dialog(azonosito);
                            }
                        },
                        @endif
                        {
                            text: '<i class="fa fa-spinner text-white" style="font-size: 16px" aria-hidden="true" title="Táblázat frissítése"></i>',
                            className: ' blue-gradient waves-effect',
                            action: function (e, dt, node, config) {
                                dt.ajax.reload();
                            }
                        },


                    ]
                });
                $('#tankolas_table tbody').on('click', 'tr', function () {
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


        function dialog(id){
            $("#dialog-confirm").dialog({
                resizable: false,
                height: "auto",
                width: 400,
                modal: false,
                autoOpen: false,
                buttons: {
                    "Megerösít": function() {
                        trash(id);
                    },
                    "Mégse": function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
            $("#dialog-confirm").dialog( "open" );
        }
        function trash(id) {
            $("#dialog-confirm").dialog( "close" );
            $.ajax({
                url: "{{route('tankolas.delete')}}",
                method: 'POST',
                data: { azonosito: id, _token: "{{csrf_token()}}"},
                context: document.body
            }).done(function (data) {
                $('#tankolas_table').DataTable().ajax.reload();
            });
        }

    </script>
    <script src="{{asset('jquery-ui/jquery-ui.min.js')}}"></script>


    <!-- <script defer  src="{{asset('js/DataTable/bootstrap.min.js')}}"></script> -->
    <script defer  src="{{asset('js/DataTable/dataTables.bootstrap4.min.js')}}"></script>
    <script defer  src="{{asset('js/DataTable/dataTables.responsive.min.js')}}"></script>
    <script defer  src="{{asset('js/DataTable/dataTables.buttons.min.js')}}"></script>
    <script defer  src="{{asset('js/DataTable/responsive.bootstrap4.min.js')}}"></script>
    <script defer  src="{{asset('js/DataTable/buttons.bootstrap4.min.js')}}"></script>

@endsection
