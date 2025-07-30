<!-- Modal for Add Item -->
<div class="modal fade" id="itemModal{{ $transaksi->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
            <input type="hidden" name="status" value="1">
            <!-- Cicil default -->
            <input type="hidden" name="nominal" value="0">
            <!-- Hidden -->

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Item</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <div id="modalDetails{{ $transaksi->id }}">
                        <div class="row mb-3 align-items-end">
                            <div class="col-md-4">
                                <label>Berat</label>
                                <select name="details[0][berat]" class="form-control" required>
                                    <option value="5">5 Kg</option>
                                    <option value="10">10 Kg</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Qty</label>
                                <input type="number" name="details[0][qty]" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label>Harga</label>
                                <input type="number" name="details[0][harga]" class="form-control" required>
                                <input type="hidden" name="details[0][satuan]" value="Kg">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary addModalDetail"
                        data-id="{{ $transaksi->id }}">
                        + Tambah Baris
                    </button>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan
                        Item</button>
                </div>
            </div>
        </form>
    </div>
</div>
