<!-- Modal Detail Transaksi -->
<div class="modal fade" id="detailModal{{ $transaksi->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi - {{ $transaksi->no_inv }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p><strong>Customer:</strong> {{ $transaksi->customer ?? '-' }}</p>
                <p><strong>Tanggal:</strong>
                    {{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d M Y H:i') }}</p>
                <p><strong>User:</strong> {{ $transaksi->user->name }}</p>

                <hr>
                <h6>Detail Item</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Berat</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi->details as $detail)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('d F Y') }}
                                </td>
                                <td>{{ $detail->berat }} {{ $detail->satuan }}</td>
                                <td>{{ $detail->qty }}</td>
                                <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>
                <h6>Pembayaran</h6>
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi->bayar as $bayar)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($bayar->created_at)->translatedFormat('d M Y H:i') }}</td>
                                <td>Rp {{ number_format($bayar->nominal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="font-weight-bold">
                            <td>Total Dibayar</td>
                            <td>Rp {{ number_format($transaksi->bayar->sum('nominal'), 0, ',', '.') }}</td>
                        </tr>
                        @if ($transaksi->status != 0)
                            <tr class="text-danger font-weight-bold">
                                <td>Sisa</td>
                                <td>Rp
                                    {{ number_format($transaksi->total - $transaksi->bayar->sum('nominal'), 0, ',', '.') }}
                                </td>
                            </tr>
                        @else
                            <tr class="text-success font-weight-bold">
                                <td colspan="2" class="text-center">Lunas</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <hr>
                <h6>Hutang Besar</h6>
                @if ($transaksi->utang)
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($transaksi->utang->tanggal)->translatedFormat('d M Y') }}
                                    {{ \Carbon\Carbon::parse($transaksi->utang->created_at)->translatedFormat(' H:i') }}
                                </td>
                                <td>Rp {{ number_format($transaksi->utang->nominal, 0, ',', '.') }}</td>
                                <td>
                                    @if ($transaksi->utang->status == 1)
                                        <span class="text-success">Lunas</span>
                                    @else
                                        <span class="text-danger">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @if ($transaksi->utang->cicilans->isNotEmpty())
                        <h6>Rincian Cicilan Hutang</h6>
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nominal</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi->utang->cicilans as $cicil)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($cicil->tanggal ?? $cicil->created_at)->translatedFormat('d M Y H:i') }}
                                        </td>
                                        <td>Rp {{ number_format($cicil->nominal, 0, ',', '.') }}</td>
                                        <td>{{ $cicil->user->name ?? '-' }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td>Total Cicilan</td>
                                    <td colspan="2">Rp
                                        {{ number_format($transaksi->utang->cicilans->sum('nominal'), 0, ',', '.') }}
                                    </td>
                                </tr>
                                @if ($transaksi->utang->status == 0)
                                    <tr class="text-danger font-weight-bold">
                                        <td>Sisa</td>
                                        <td colspan="2">
                                            Rp
                                            {{ number_format($transaksi->utang->nominal - $transaksi->utang->cicilans->sum('nominal'), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @else
                                    <tr class="text-success font-weight-bold">
                                        <td colspan="3" class="text-center">Hutang Lunas</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif
                @else
                    <p class="text-muted small">Tidak ada hutang untuk transaksi ini.</p>
                @endif

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
