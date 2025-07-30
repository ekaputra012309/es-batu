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
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>: {{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>User</th>
            <td>: {{ $transaksi->user->name }}</td>
        </tr>
        <tr>
            <th>Customer</th>
            <td>: {{ $transaksi->customer ?? '' }}</td>
        </tr>
    </table>

    <h3>Detail Transaksi</h3>
    <table>
        @foreach ($transaksi->details as $detail)
            <tr>
                <td>{{ $detail->berat }} {{ $detail->satuan }} ( {{ $detail->qty }} pcs x Rp
                    {{ number_format($detail->harga, 0, ',', '.') }} )</td>
                <td class="price">Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td>Total</td>
            <td class="price">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
        </tr>

        @php
            $totalBayar = $transaksi->bayar->sum('nominal');
            $sisa = $transaksi->total - $totalBayar;
        @endphp

        <tr>
            <td colspan="2"><strong>Detail Pembayaran:</strong></td>
        </tr>
        @foreach ($transaksi->bayar as $bayar)
            <tr>
                <td>- {{ \Carbon\Carbon::parse($bayar->created_at)->translatedFormat('d M Y H:i') }}</td>
                <td class="price">Rp {{ number_format($bayar->nominal, 0, ',', '.') }}</td>
            </tr>
        @endforeach

        <tr class="total-row">
            <td>Total Dibayar</td>
            <td class="price">Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
        </tr>

        @if ($transaksi->status == 0)
            <tr>
                <td colspan="2" class="text-center"><strong>Transaksi sudah lunas</strong></td>
            </tr>
        @else
            <tr class="total-row">
                <td>Sisa Pembayaran</td>
                <td class="price text-danger">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center"> <strong>Harap segera melunasi sisa pembayaran</strong></td>
            </tr>
        @endif
    </table>

    <div class="footer">
        <p>Terima Kasih!</p>
        <p>Silakan Kunjungi Kami Lagi!</p>
    </div>
</body>

</html>
