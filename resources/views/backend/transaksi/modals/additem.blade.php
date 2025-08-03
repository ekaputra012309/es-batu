{{-- Modal Add Item Detail --}}
<div class="modal fade" id="addItemModal{{ $transaksi->id }}" tabindex="-1" role="dialog"
    aria-labelledby="addItemModalLabel{{ $transaksi->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('transaksi.detail.store', ['transaksiId' => $transaksi->id]) }}" method="POST"
                    class="detail-form" data-transaksi-id="{{ $transaksi->id }}">
                    @csrf
                    @auth
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    @endauth

                    <div class="form-group col-md-3">
                        <label>Tanggal Transaksi</label>
                        <input type="date" name="tanggal" class="form-control " value="{{ now()->format('Y-m-d') }}">
                    </div>

                    <div id="detail-container-{{ $transaksi->id }}"></div>

                    <button type="button" class="btn btn-sm btn-secondary mb-2 add-detail-btn"
                        data-transaksi-id="{{ $transaksi->id }}">+ Tambah Detail</button>
                    <br>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
