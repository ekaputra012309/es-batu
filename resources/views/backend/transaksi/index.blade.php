@php
    $role = App\Models\Privilage::getRoleKodeForAuthenticatedUser();
@endphp

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
                                <h3 class="card-title">Tambah Transaksi</h3>
                                <div class="card-tools">
                                    {{-- @if (session('print_transaction_id') != 0)
                                        <button id="printButton" onclick="openTab()" class="btn btn-primary btn-sm d-none">
                                            <i class="fas fa-print"></i> Print Data
                                        </button>

                                        <script>
                                            function openTab() {
                                                const printTransactionId = @json(session('print_transaction_id'));
                                                const printUrl = "{{ url('transaksi/print') }}" + '/' + printTransactionId;
                                                window.open(printUrl, "_blank");
                                            }

                                            setTimeout(function() {
                                                document.getElementById('printButton').click();
                                            }, 3000);
                                        </script>
                                    @endif --}}

                                </div>
                            </div>
                            <form id="paymentForm" action="{{ route('transaksi.store') }}" method="POST">
                                @csrf
                                @auth
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                @endauth

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h4 class="mb-4">Transaksi Detail</h4>
                                            <div class="form-group">
                                                <label for="customer">Customer:</label>
                                                <input type="text" class="form-control" name="customer">
                                            </div>
                                            <div id="details">
                                                <div class="row mb-3 align-items-end">
                                                    <div class="col-12 col-md-4">
                                                        <label for="berat">Berat (KG):</label>
                                                        <select name="details[0][berat]" class="form-control" required>
                                                            <option value="">Pilih Varian</option>
                                                            <option value="5">5 Kg</option>
                                                            <option value="10">10 Kg</option>
                                                            <option value="20">20 Kg</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label for="qty">Kuantiti (PCS):</label>
                                                        <input type="number" class="form-control qty"
                                                            name="details[0][qty]" required>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <label for="harga">Harga:</label>
                                                        <input type="tel" class="form-control price"
                                                            name="details[0][harga]" required>
                                                        <input type="hidden" name="details[0][satuan]" value="Kg">
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-secondary mt-2" id="addDetail">Tambah
                                                Detail</button>
                                        </div>

                                        <div class="col-md-4 text-center mt-4">
                                            <h3 class="mb-4">Total Bayar</h3>
                                            <div id="totalAmount" class="p-3 bg-light rounded">
                                                Rp <br> <span id="total" style="font-size: 40pt">0.00</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <button type="submit" class="btn btn-primary mt-3">Simpan Transaksi</button> --}}
                                    <button type="button" class="btn btn-primary mt-3" id="triggerModal">Simpan
                                        Transaksi</button>
                                </div>
                            </form>

                            @include('backend.transaksi.modals.payment')

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 150px">#</th>
                                            <th>No Invoice</th>
                                            <th>Tanggal Transaksi</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>User</th>
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
                                                    @if (in_array($role, ['superadmin', 'admin']))
                                                        <a class="btn btn-xs btn-danger"
                                                            href="{{ route('transaksi.destroy', $transaksi->id) }}"
                                                            data-confirm-delete="true">
                                                            <i class="fas fa-trash"></i>
                                                            Delete
                                                        </a>
                                                    @endif
                                                    <a class="btn btn-xs btn-info"
                                                        href="{{ route('transaksi.show', $transaksi->id) }}"
                                                        target="_blank">
                                                        <i class="fas fa-search"></i>
                                                        Detail
                                                    </a>

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
                                                    <strong>{{ $transaksi->no_inv }}</strong>
                                                    <br>
                                                    <span class="text-muted">
                                                        {{ $transaksi->customer }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-calendar small"></i>
                                                    {{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d F Y') }}
                                                    <br>
                                                    <i class="fas fa-clock small"></i>
                                                    {{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('H:i') }}
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
                                                <td>{{ $transaksi->user->name }}</td>
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

        <script>
            $("#example1").DataTable({
                "responsive": false,
                "scrollX": true,
                "lengthChange": true,
                "autoWidth": false, // usually better to disable this for scroll
                "scrollCollapse": true,
                "paging": true,
                // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        </script>

        @include('backend.transaksi.script.transaksi')
    </div>
@endsection
