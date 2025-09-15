<!DOCTYPE html>
<html>

<head>
    <link rel="shortcut icon" href="{{ asset('backend/img/logo.ico') }}" type="image/x-icon">
    <title>Slip Gaji - {{ $slip->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 100%;
            margin: 0;
            padding: 0;
            font-size: 11px;
        }

        h1,
        h3 {
            text-align: center;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            padding: 2px;
            line-height: 1.4;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
        }

        .price {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            border-top: 0.5px solid #000;
        }
    </style>
</head>

<body>
    <h3>{{ config('app.name') }}</h3>

    <table style="width: 50%">
        <tr>
            <th>Nama</th>
            <td>: {{ $slip->nama }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>: {{ \Carbon\Carbon::parse($slip->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <th>Hari Kerja</th>
            <td>: {{ $slip->hari_kerja }} hari</td>
        </tr>
        <tr>
            <th>User Input</th>
            <td>: {{ $slip->user->name ?? '-' }}</td>
        </tr>
    </table>

    <h3>Rincian Gaji</h3>
    @php
        $rupiah = fn($value) => 'Rp ' . number_format($value, 0, ',', '.');

        $gajiKotor = $slip->gp + $slip->inssentif + $slip->bonus + $slip->uang_makan;
        $potongan = $slip->pot_uang_makan + $slip->pot_kasbon;
        $totalBersih = $gajiKotor - $potongan;
        $hutangA = $slip->hutang - $slip->pot_kasbon;
    @endphp

    <table>
        <tr>
            <td>Gaji Pokok</td>
            <td class="price">{{ $rupiah($slip->gp) }}</td>
        </tr>
        <tr>
            <td>Insentif</td>
            <td class="price">{{ $rupiah($slip->inssentif) }}</td>
        </tr>
        <tr>
            <td>Bonus</td>
            <td class="price">{{ $rupiah($slip->bonus) }}</td>
        </tr>
        <tr>
            <td>Uang Makan</td>
            <td class="price">{{ $rupiah($slip->uang_makan) }}</td>
        </tr>
        <tr class="total-row">
            <td>Total Gaji Kotor</td>
            <td class="price">{{ $rupiah($gajiKotor) }}</td>
        </tr>

        <tr>
            <td>Potongan Uang Makan</td>
            <td class="price">- {{ $rupiah($slip->pot_uang_makan) }}</td>
        </tr>
        <tr>
            <td>Potongan Kasbon</td>
            <td class="price">- {{ $rupiah($slip->pot_kasbon) }}</td>
        </tr>
        <tr class="total-row">
            <td>Gaji Bersih</td>
            <td class="price">{{ $rupiah($totalBersih) }}</td>
        </tr>

        <tr>
            <td>Sisa Hutang/Kasbon</td>
            <td class="price">- {{ $rupiah($hutangA) }}</td>
        </tr>
    </table>
</body>

</html>
