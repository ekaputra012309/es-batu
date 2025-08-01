@extends('backend/template/app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Transaksi</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Transaksi</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Laporan Transaksi</h3>
                            </div>
                            <form action="{{ route('transaksi.laporan.cetak') }}" target="_blank" method="GET">
                                @csrf
                                @auth
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                @endauth

                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <div class="row w-100">
                                        <div class="col-lg-4 col-md-6 col-sm-8 col-12 mx-auto">

                                            <!-- Select Report Type -->
                                            <div class="form-group mb-3">
                                                <label for="report_type">Jenis Laporan</label>
                                                <select class="form-control" name="report_type" id="report_type" required>
                                                    <option value="">-- Pilih Jenis --</option>
                                                    <option value="transaksi">Laporan Transaksi</option>
                                                    <option value="pengeluaran">Laporan Pengeluaran</option>
                                                </select>
                                            </div>

                                            <!-- Date Range -->
                                            <div class="form-group">
                                                <label for="daterange">Tanggal</label>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-default w-100" id="daterange-btn">
                                                    <i class="far fa-calendar-alt"></i> Pilih Tanggal
                                                </button>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" id="daterange" class="form-control" readonly>
                                                <input type="hidden" name="startDate" id="startDate" class="form-control"
                                                    readonly>
                                                <input type="hidden" name="endDate" id="endDate" class="form-control"
                                                    readonly>
                                            </div>

                                            <!-- Submit -->
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-primary w-100">Print Laporan</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            $(function() {
                $('#daterange-btn').daterangepicker({
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                                'month').endOf('month')]
                        },
                        startDate: moment().subtract(29, 'days'),
                        endDate: moment()
                    },
                    function(start, end) {
                        $('#daterange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                        $('#startDate').val(start.format('YYYY-MM-DD'));
                        $('#endDate').val(end.format('YYYY-MM-DD'))
                    }
                );
            });
        </script>
    </div>
@endsection
