<!DOCTYPE html>
<html>

<head>
    <link rel="shortcut icon" href="{{ asset('backend/img/logo.ico') }}" type="image/x-icon">
    <title>Transaksi Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            /* width: 58mm; Set width for thermal paper */
            width: 100%;
            margin: 0;
            padding: 0;
            font-size: 11px;
        }

        h1 {
            text-align: center;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            /* Remove margin */
        }

        th,
        td {
            text-align: left;
            padding: 2px;
            /* Minimal padding */
            line-height: 1.2;
            /* Adjust line height if needed */
        }

        .footer {
            /* margin-top: 10px; */
            text-align: center;
        }

        .price {
            text-align: right;
            /* Align price to the right */
        }

        .total-row {
            font-weight: bold;
            text-align: right;
            border-top: 0.1px solid #000;
            /* Align total to the right */
        }
    </style>
</head>

<body>
    <h3 class="text-center">{{ config('app.name') }}</h3>
    <table>
        <tr>
            <th>No Invoice</th>
            <td>: {{ $transaksi->no_inv }}</td>
            <th>User</th>
            <td>: {{ $transaksi->user->name }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>: {{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d/m/Y H:i') }}</td>
            <th>Customer</th>
            <td>: {{ $transaksi->customer ?? '' }}</td>
        </tr>
    </table>

    <h3>Detail Transaksi</h3>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Berat</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $detail->tanggal ? \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('d F Y') : '-' }}
                    </td>
                    <td>{{ $detail->berat }} {{ $detail->satuan }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td class="price">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="price">Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" style="text-align: right;"><strong>Total</strong></td>
                <td class="price"><strong>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
    <h3>Detail Pembayaran</h3>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Bayar</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalBayar = 0;
            @endphp
            @forelse ($transaksi->bayar as $i => $bayar)
                @php
                    $totalBayar += $bayar->nominal;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($bayar->created_at)->translatedFormat('d F Y H:i') }}</td>
                    <td class="price">Rp {{ number_format($bayar->nominal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Belum ada pembayaran</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="2" style="text-align: right;"><strong>Total Tagihan</strong></td>
                <td class="price"><strong>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</strong></td>
            </tr>
            <tr class="total-row">
                <td colspan="2" style="text-align: right;"><strong>Total Dibayar</strong></td>
                <td class="price"><strong>Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong></td>
            </tr>
            @if ($transaksi->status != 0)
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">Sisa Pembayaran</td>
                    <td class="price text-danger">Rp {{ number_format($transaksi->total - $totalBayar, 0, ',', '.') }}
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="3" style="text-align: center;"><strong>Transaksi sudah lunas</strong></td>
                </tr>
            @endif
        </tbody>
    </table>

    <h3>Detail Utang</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Total Hutang</th>
                <th>Total Cicilan</th>
                <th>Sisa Hutang</th>
            </tr>
        </thead>
        <tbody>
            @if ($transaksi->utang)
                @php
                    $totalCicil = $transaksi->utang->cicilans?->sum('nominal') ?? 0;
                    $sisaUtang = $transaksi->utang->nominal - $totalCicil;
                @endphp
                <tr>
                    <td class="price">Rp {{ number_format($transaksi->utang->nominal, 0, ',', '.') }}</td>
                    <td class="price">Rp {{ number_format($totalCicil, 0, ',', '.') }}</td>
                    <td class="price {{ $sisaUtang > 0 ? 'text-danger' : '' }}">
                        Rp {{ number_format($sisaUtang, 0, ',', '.') }}
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="3" style="text-align:center;">Tidak ada data utang</td>
                </tr>
            @endif
        </tbody>
    </table>

    <h3>Detail Cicilan</h3>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Cicil</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @if ($transaksi->utang && $transaksi->utang->cicilans && $transaksi->utang->cicilans->count() > 0)
                @php
                    $totalCicil = 0;
                @endphp
                @foreach ($transaksi->utang->cicilans as $i => $cicil)
                    @php $totalCicil += $cicil->nominal; @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($cicil->tanggal)->translatedFormat('d F Y H:i') }}</td>
                        <td class="price">Rp {{ number_format($cicil->nominal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2" style="text-align:right;"><strong>Total Hutang</strong></td>
                    <td class="price"><strong>Rp {{ number_format($transaksi->utang->nominal, 0, ',', '.') }}</strong>
                    </td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" style="text-align:right;"><strong>Total Cicilan</strong></td>
                    <td class="price"><strong>Rp {{ number_format($totalCicil, 0, ',', '.') }}</strong></td>
                </tr>
                @if ($transaksi->utang)
                    @php
                        $totalCicil = $transaksi->utang->cicilans->sum('nominal') ?? 0;
                        $sisaUtang = $transaksi->utang->nominal - $totalCicil;
                    @endphp

                    @if ($sisaUtang > 0)
                        <tr class="total-row">
                            <td colspan="2" style="text-align:right;">Sisa Hutang</td>
                            <td class="price text-danger">
                                Rp {{ number_format($sisaUtang, 0, ',', '.') }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="3" style="text-align:center;">
                                <strong>Hutang sudah lunas</strong>
                            </td>
                        </tr>
                    @endif
                @endif
            @else
                <tr>
                    <td colspan="3" style="text-align:center;">Belum ada cicilan</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Terima Kasih!, Silakan Kunjungi Kami Lagi!</p>
    </div>
</body>

</html>
