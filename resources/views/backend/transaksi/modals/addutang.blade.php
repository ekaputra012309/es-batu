{{-- Modal Add Item Detail --}}
<div class="modal fade" id="addUtangModal{{ $transaksi->id }}" tabindex="-1" role="dialog"
    aria-labelledby="addUtangModalLabel{{ $transaksi->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('utang.besar.store', ['transaksiId' => $transaksi->id]) }}" method="POST"
                    class="row g-2 align-items-end detail-form" data-transaksi-id="{{ $transaksi->id }}">
                    @csrf
                    @auth
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    @endauth

                    <div class="col-md-4">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ now()->format('Y-m-d') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jumlah Utang</label>
                        <input type="text" class="form-control nominal-input" placeholder="Nominal" required>
                        <input type="hidden" name="nominal" class="nominal-hidden">
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    function formatRupiah(angka) {
        return angka.replace(/\D/g, '')
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function parseRupiah(str) {
        return str.replace(/\./g, '');
    }

    // Handle keyup formatting
    $(document).on('keyup', '.nominal-input', function() {
        let formatted = formatRupiah($(this).val());
        $(this).val(formatted);

        let cleanValue = parseRupiah(formatted);
        $(this).siblings('.nominal-hidden').val(cleanValue);
    });
</script>
