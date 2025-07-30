<!-- Modal Cicil -->
<div class="modal fade" id="cicilModal{{ $transaksi->id }}" tabindex="-1" role="dialog"
    aria-labelledby="cicilModalLabel{{ $transaksi->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('transaksi.cicil', $transaksi->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bayar Cicil - {{ $transaksi->no_inv }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Total Transaksi: <strong>Rp
                            {{ number_format($transaksi->total, 2, ',', '.') }}</strong>
                    </p>
                    <p>Total Dibayar: <strong>Rp
                            {{ number_format($totalBayar, 2, ',', '.') }}</strong>
                    </p>
                    <p>Sisa Pembayaran: <strong class="text-danger">Rp
                            {{ number_format($sisa, 2, ',', '.') }}</strong></p>

                    <div class="form-group">
                        <label for="nominal">Nominal Pembayaran</label>
                        <input type="text" name="nominal_display" class="form-control"
                            id="nominalFormatted{{ $transaksi->id }}" required>
                        <input type="hidden" name="nominal" id="nominalRaw{{ $transaksi->id }}">
                        {{-- <input type="number" name="nominal" class="form-control" required max="{{ $sisa }}"> --}}
                        <small class="text-muted">Maksimum: Rp
                            {{ number_format($sisa, 2, ',', '.') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Bayar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalId = {{ $transaksi->id }};
        const formattedInput = document.getElementById(`nominalFormatted${modalId}`);
        const rawInput = document.getElementById(`nominalRaw${modalId}`);

        if (formattedInput && rawInput) {
            formattedInput.addEventListener('input', function() {
                let value = this.value.replace(/\./g, '').replace(/[^\d]/g, '');
                if (!value) value = '0';

                rawInput.value = value;

                // Format with dot as thousand separator
                this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            });
        }
    });
</script>
