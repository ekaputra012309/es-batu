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
                                            <th>Status</th>
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
                                                            data-target="#itemModal{{ $transaksi->id }}">
                                                            <i class="fas fa-plus"></i> Add Item
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
                                            </tr>

                                            @include('backend.transaksi.modals.additem', [
                                                'transaksi' => $transaksi,
                                            ])
                                        @endforeach

                                        @include('backend.transaksi.script.additem')

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
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
