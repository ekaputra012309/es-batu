@extends('backend/template/app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <!-- You can add breadcrumb links here if needed -->
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <!-- Daily Income Card -->
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Pendapatan per Hari</h3>
                            </div>
                            <div class="card-body">
                                <div class="info-box">
                                    <span class="info-box-icon"><i class="fas fa-calendar-day"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-number">
                                            <h3 class="font-weight-bold">Rp {{ number_format($todayIncome, 0, ',', '.') }}
                                            </h3>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Monthly Income Card -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Pendapatan per Bulan</h3>
                            </div>
                            <div class="card-body">
                                <div class="info-box">
                                    <span class="info-box-icon"><i class="fas fa-calendar-week"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-number">
                                            <h3 class="font-weight-bold">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}
                                            </h3>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Yearly Income Card -->
                        <div class="card card-danger">
                            <div class="card-header">
                                <h3 class="card-title">Pendapatan per Tahun</h3>
                            </div>
                            <div class="card-body">
                                <div class="info-box">
                                    <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-number">
                                            <h3 class="font-weight-bold">Rp {{ number_format($yearlyIncome, 0, ',', '.') }}
                                            </h3>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Transaksi Belum Lunas</h3>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 150px">#</th>
                                            <th>Pelanggan</th>
                                            <th>Total</th>
                                            <th>Status Tagihan</th>
                                            <th>Status Hutang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datatransaksi as $transaksi)
                                            @php
                                                $totalBayar = $transaksi->bayar ? $transaksi->bayar->sum('nominal') : 0;
                                                $sisa = $transaksi->total - $totalBayar;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <a class="btn btn-xs btn-primary"
                                                        href="{{ route('transaksi.print', $transaksi->id) }}"
                                                        target="_blank">
                                                        <i class="fas fa-print"></i>
                                                        Print
                                                    </a>
                                                    <button class="btn btn-xs btn-info" data-toggle="modal"
                                                        data-target="#detailModal{{ $transaksi->id }}">
                                                        <i class="fas fa-search"></i> Detail
                                                    </button>

                                                    @include('backend.transaksi.modals.detail', [
                                                        'transaksi' => $transaksi,
                                                    ])

                                                    {{-- Button on right, only show if not lunas --}}
                                                    @if ($transaksi->status != 0)
                                                        <br>
                                                        <button class="btn btn-xs btn-secondary" data-toggle="modal"
                                                            data-target="#cicilModal{{ $transaksi->id }}">
                                                            <i class="fas fa-hashtag"></i> Bayar Cicil
                                                        </button>

                                                        <button class="btn btn-xs btn-success" data-toggle="modal"
                                                            data-target="#addItemModal{{ $transaksi->id }}">
                                                            <i class="fas fa-plus"></i> Add Item
                                                        </button>

                                                        <button class="btn btn-xs btn-primary" data-toggle="modal"
                                                            data-target="#editItemModal{{ $transaksi->id }}">
                                                            <i class="fas fa-edit"></i> Edit Item
                                                        </button>
                                                    @endif

                                                    {{-- button hutang --}}
                                                    {{-- Belum ada utang → boleh tambah utang --}}
                                                    @if (!$transaksi->utang)
                                                        <br>
                                                        <button class="btn btn-xs btn-success" data-toggle="modal"
                                                            data-target="#addUtangModal{{ $transaksi->id }}">
                                                            <i class="fas fa-plus"></i> Tambah Utang Besar
                                                        </button>
                                                    @endif

                                                    {{-- Sudah ada utang dan masih status 0 (belum lunas) → boleh cicil --}}
                                                    @if ($transaksi->utang && $transaksi->utang->status == 0)
                                                        <br>
                                                        <button class="btn btn-xs btn-primary" data-toggle="modal"
                                                            data-target="#cicilUtangModal{{ $transaksi->utang->id }}">
                                                            <i class="fas fa-hashtag"></i> Cicil Utang Besar
                                                        </button>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>
                                                        <h4>
                                                            {{ $transaksi->customer }}
                                                        </h4>
                                                    </strong>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Rp</span>
                                                        <span>{{ number_format($transaksi->total, 0, ',', '.') }}</span>
                                                    </div>
                                                    @if ($transaksi->status != 0)
                                                        <span class="small text-muted">Sisa Pembayaran</span> <br>
                                                        <div class="d-flex justify-content-between text-danger">
                                                            <span>Rp</span>
                                                            <span>{{ number_format($sisa, 0, ',', '.') }}</span>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        {{-- Status on left --}}
                                                        @if ($transaksi->status == 0)
                                                            <span class="badge badge-success">Lunas</span>
                                                        @else
                                                            <span class="badge badge-danger">Belum Lunas</span>
                                                        @endif
                                                    </div>

                                                    @foreach ($transaksi->bayar as $byr)
                                                        <div class="mt-1 small text-muted d-flex justify-content-between">
                                                            <span>Rp
                                                                {{ number_format($byr->nominal, 0, ',', '.') }}</span>
                                                            <span>{{ \Carbon\Carbon::parse($byr->created_at)->translatedFormat('d M ,H:i') }}</span>
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        {{-- Tampilkan status hanya jika ada utang --}}
                                                        @if ($transaksi->utang)
                                                            @if ($transaksi->utang->status == 1)
                                                                <span class="badge badge-success">Lunas</span>
                                                            @else
                                                                <span class="badge badge-danger">Belum Lunas</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    @if (optional($transaksi->utang)->nominal)
                                                        <div class="mt-1 small text-muted d-flex justify-content-between">
                                                            <span>Rp
                                                                {{ number_format(optional($transaksi->utang)->nominal, 0, ',', '.') }}</span>
                                                            <span>{{ \Carbon\Carbon::parse(optional($transaksi->utang)->created_at)->translatedFormat('d M ,H:i') }}</span>
                                                        </div>
                                                    @endif

                                                    @if ($transaksi->utang?->cicilans->isNotEmpty())
                                                        <span class="muted small">Rincian Cicilan Hutang</span>
                                                    @endif

                                                    @foreach (optional($transaksi->utang)->cicilans ?? [] as $cicil)
                                                        <div class="mt-1 small text-muted d-flex justify-content-between">
                                                            <span>Rp
                                                                {{ number_format($cicil->nominal, 0, ',', '.') }}</span>
                                                            <span>{{ \Carbon\Carbon::parse($cicil->created_at)->translatedFormat('d M ,H:i') }}</span>
                                                        </div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                @foreach ($datatransaksi as $transaksi)
                                    @if ($transaksi->status != 0)
                                        @php
                                            $totalBayar = $transaksi->bayar ? $transaksi->bayar->sum('nominal') : 0;
                                            $sisa = $transaksi->total - $totalBayar;
                                        @endphp

                                        @include('backend.transaksi.modals.cicil', [
                                            'transaksi' => $transaksi,
                                        ])

                                        @include('backend.transaksi.modals.additem', [
                                            'transaksi' => $transaksi,
                                        ])

                                        @include('backend.transaksi.modals.d_edititem', [
                                            'transaksi' => $transaksi,
                                        ])
                                    @endif
                                    @include('backend.transaksi.modals.addutang', [
                                        'transaksi' => $transaksi,
                                    ])

                                    @if ($transaksi->utang)
                                        @php
                                            $totalBayarUtang =
                                                optional($transaksi->utang->cicilans)->sum('nominal') ?? 0;
                                            $sisaUtang = $transaksi->utang->nominal - $totalBayarUtang;
                                        @endphp

                                        @include('backend.transaksi.modals.cicilutang2', [
                                            'utang' => $transaksi->utang,
                                        ])
                                    @endif
                                    <script>
                                        window.detailIndices = {};
                                    </script>

                                    @include('backend.transaksi.script.additem', [
                                        'transaksi' => $transaksi,
                                    ])

                                    @include('backend.transaksi.script.edititem', [
                                        'transaksi' => $transaksi,
                                    ])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <script>
            $("#example1").DataTable({
                "responsive": false,
                "scrollX": true,
                "lengthChange": true,
                "autoWidth": false, // usually better to disable this for scroll
                "scrollCollapse": true,
                "paging": true,
                "pageLength": 5,
                // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        </script>
    </div>
@endsection
