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
            border: 1px solid #000; /* Border for all cells */
        }
        td {
            text-align: right;
            vertical-align: top;
            padding: 5px;
            line-height: 1.2;
            border: 1px solid #000; /* Border for all cells */
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
    </style>
</head>
<body>
    <h3>{{ config('app.name') }}</h3>
    <p>
        Laporan dari tanggal 
        {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d 
        {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Petugas</th>
                <th>No Invoice</th>
                <th>Tanggal Transaksi</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotal = 0;
                $rowNumber = 0;
            @endphp
            @foreach ($datatransaksi as $transaksi)
                <tr>
                    <td style="text-align: center;">{{ ++$rowNumber }}</td>
                    <td>{{ $transaksi->user->name }}</td>
                    <td>{{ $transaksi->no_inv }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d F Y, H:i') }}</td>
                    <td>
                        @foreach ($transaksi->details as $detail)
                            {{ $detail->qty }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($transaksi->details as $detail)
                            Rp {{ number_format($detail->harga, 0, ',', '.') }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($transaksi->details as $detail)
                            <!-- Rp {{ number_format($detail->qty*$detail->harga, 0, ',', '.') }}<br> -->
                            Rp {{ number_format($detail->harga, 0, ',', '.') }}<br>
                        @endforeach
                    </td>
                    <td class="price">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                </tr>
                @php
                    $grandTotal += $transaksi->total; // Sum total for the invoice
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7"><b>Grand Total</b></td>
                <td class="price"><b>Rp {{ number_format($grandTotal, 0, ',', '.') }}</b></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
