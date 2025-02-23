<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('backend/img/logo.ico') }}" type="image/x-icon">
    <title>{{ $title . config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('backend/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/adminlte.min.css?v=3.2.0') }}">

    <script src="{{ asset('backend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/js/adminlte.min.js?v=3.2.0') }}"></script>
    <script src="{{ asset('backend/js/jquery.overlayScrollbars.min.js') }}"></script>

    {{-- datatables css --}}
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    {{-- <link rel="stylesheet" href="{{ asset('backend/css/datatables/dataTables.bootstrap4.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('backend/css/datatables/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/datatables/buttons.bootstrap4.min.css') }}">
    {{-- datatables js --}}
    <script src="{{ asset('backend/js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.colVis.min.js') }}"></script>
    {{-- select2 css js --}}
    <link rel="stylesheet" href="{{ asset('backend/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/select2-bootstrap4.min.css') }}">
    <script src="{{ asset('backend/js/select2.full.min.js') }}"></script>
    {{-- sweef alert --}}
    <link rel="stylesheet" href="{{ asset('backend/css/sweetalert2.min.css') }}">
    <script src="{{ asset('backend/js/sweetalert2.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('backend/moment/moment.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('backend/css/daterangepicker/daterangepicker.css') }}">
    <script src="{{ asset('backend/js/daterangepicker/daterangepicker.js') }}"></script>

    <style>
        [class*="sidebar-light-"] .nav-treeview>.nav-item>.nav-link.active,
        [class*="sidebar-light-"] .nav-treeview>.nav-item>.nav-link.active:hover {
            background-color: #007bff !important;
            color: #fff !important;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table td {
            white-space: nowrap;
        }

        .input-qty,
        .input-tarif,
        .input-discount,
        .input-subtotal {
            min-width: 80px;
        }

        .input-tarif {
            min-width: 120px;
        }

        .input-subtotal {
            min-width: 140px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('backend/template/header')
        @include('backend/template/sidebar')
        @include('sweetalert::alert')
        @yield('content')

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>&copy; {{ date('Y') == 2025 ? '2025' : '2025-' . date('Y') }}
                <a href="https://dnakonsultan.com">
                {{ config('app.name') }}
                </a>
            </strong>

            All rights
            reserved.
        </footer>

        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '{{ route("get.role.name") }}',
                type: 'GET',
                success: function(response) {
                    if (response.role_name) {
                        $('.roleuser').text(response.role_name);
                    } else {
                        console.log("Role Name not found");
                    }
                },
                error: function(error) {
                    console.log("Error fetching role name");
                }
            });
        });
    </script>
</body>

</html>