@extends('backend/template/app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Slip Gaji</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
                            {{-- <li class="breadcrumb-item"><a href="#">Layout</a></li> --}}
                            <li class="breadcrumb-item active">Slip Gaji</li>
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
                                <h3 class="card-title"> </h3>
                                <div class="card-tools">
                                    <a href="{{ route('slip.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Add Data
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tanggal</th>
                                            <th>Nama</th>
                                            <th>Gaji Pokok</th>
                                            <th>Gaji di terima</th>
                                            <th>Sisa Hutang/Kasbon</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataslip as $slip)
                                            @php
                                                $gp = 0;
                                                $gp =
                                                    $slip->gp +
                                                    $slip->inssentif -
                                                    ($slip->pot_uang_makan + $slip->pot_kasbon);

                                                function formatRupiah($number)
                                                {
                                                    return 'Rp ' . number_format($number, 0, ',', '.');
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <a class="btn btn-xs btn-primary"
                                                        href="{{ route('slip.edit', $slip->id) }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a class="btn btn-xs btn-danger"
                                                        href="{{ route('slip.destroy', $slip->id) }}"
                                                        data-confirm-delete="true">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                    <a class="btn btn-xs btn-primary"
                                                        href="{{ route('slip.print', $slip->id) }}" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                        Print
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($slip->tanggal)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ $slip->nama }}</td>
                                                <td>{{ formatRupiah($slip->gp) }}</td>
                                                <td>{{ formatRupiah($gp) }}</td>
                                                <td>{{ formatRupiah($slip->hutang) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        </script>
    </div>
@endsection
