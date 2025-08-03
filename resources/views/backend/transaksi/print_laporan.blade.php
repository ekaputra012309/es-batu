<!DOCTYPE html>
<html>

<head>
    <link rel="shortcut icon" href="{{ asset('backend/img/logo.ico') }}" type="image/x-icon">
    <title>{{ $title . config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 100%;
            margin: 0;
            padding: 0;
            font-size: 11px;
        }

        h3 {
            text-align: center;
        }

        p {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        th {
            text-align: center;
            padding: 5px;
            line-height: 1.2;
            border: 1px solid #000;
            /* Border for all cells */
        }

        td {
            text-align: right;
            vertical-align: top;
            padding: 5px;
            line-height: 1.2;
            border: 1px solid #000;
            /* Border for all cells */
        }

        .footer {
            text-align: center;
        }

        .price {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            text-align: right;
        }

        .text-danger {
            color: red;
        }

        .text-success {
            color: green;
        }
    </style>
</head>

<body>
    <h3>{{ config('app.name') }}</h3>
    <p>
        Laporan dari tanggal
        {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d
        {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
    </p>

    @if (isset($datatransaksi))
        {{-- Transaksi Section --}}
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Petugas</th>
                    <th>No Invoice</th>
                    <th>Customer</th>
                    <th>Tanggal Transaksi</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Kurang Bayar</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotal = 0;
                    $totalKurangBayar = 0;
                    $rowNumber = 0;
                @endphp
                @foreach ($datatransaksi as $transaksi)
                    <tr>
                        <td style="text-align: center;">{{ ++$rowNumber }}</td>
                        <td>{{ $transaksi->user->name }}</td>
                        <td>{{ $transaksi->no_inv }}</td>
                        <td>{{ $transaksi->customer }}</td>
                        <td>
                            {{-- {{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d F Y, H:i') }}
                            <br> --}}
                            @if ($transaksi->details)
                                @foreach ($transaksi->details as $detail)
                                    <i class="fas fa-calendar small"></i>
                                    {{ $detail->tanggal ? \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('d F Y') : '-' }}<br>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @foreach ($transaksi->details as $detail)
                                {{ $detail->berat }} kg @ {{ $detail->qty }}pcs<br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($transaksi->details as $detail)
                                Rp {{ number_format($detail->harga, 0, ',', '.') }}<br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($transaksi->details as $detail)
                                Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}<br>
                            @endforeach
                        </td>
                        @php
                            $totalBayar = $transaksi->bayar ? $transaksi->bayar->sum('nominal') : 0;
                            $sisa = $transaksi->total - $totalBayar;
                            $status = $transaksi->status == 0 ? 'Lunas' : 'Belum Lunas';
                            $grandTotal += $transaksi->total;
                            $totalKurangBayar += $sisa;
                        @endphp
                        <td class="{{ $transaksi->status != 0 ? 'text-danger' : 'text-success' }}">{{ $status }}
                        </td>
                        <td class="price">
                            @if ($sisa > 0)
                                Rp {{ number_format($sisa, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="price">Rp {{ number_format($transaksi->total - $sisa, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7"></td>
                    <td class="price"><b>Rp {{ number_format($grandTotal, 0, ',', '.') }}</b></td>
                    <td></td>
                    <td class="price text-danger"><b>Rp {{ number_format($totalKurangBayar, 0, ',', '.') }}</b></td>
                    <td class="price text-success"><b>Rp
                            {{ number_format($grandTotal - $totalKurangBayar, 0, ',', '.') }}</b></td>
                </tr>
            </tfoot>
        </table>
    @elseif(isset($datapengeluaran))
        {{-- Pengeluaran Section --}}
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Petugas</th>
                    <th>Tanggal</th>
                    <th colspan="2">Rincian</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPengeluaran = 0;
                    $no = 1;
                @endphp
                @forelse ($datapengeluaran as $pengeluaran)
                    @php
                        $subtotal = $pengeluaran->details->sum('nominal');
                        $totalPengeluaran += $subtotal;
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $no++ }}</td>
                        <td>{{ $pengeluaran->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->translatedFormat('d F Y') }}</td>

                        <td>
                            @foreach ($pengeluaran->details as $detail)
                                <div>{{ $detail->keterangan }}</div>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($pengeluaran->details as $detail)
                                <div>{{ number_format($detail->nominal, 0, ',', '.') }}</div>
                            @endforeach
                        </td>
                        <td class="price">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Tidak ada pengeluaran</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="total-row">Total Pengeluaran</td>
                    <td class="price"><strong>Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    @endif
</body>

</html>
