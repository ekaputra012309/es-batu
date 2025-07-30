<!-- Modal Konfirmasi Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label>Status Pembayaran:</label>
                <select class="form-control" name="status" id="status" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="0">Lunas</option>
                    <option value="1">Cicil</option>
                </select>

                <label class="mt-3" for="nominal">Nominal:</label>
                <input type="text" class="form-control" name="nominal" id="nominal" required
                    placeholder="Masukkan nominal">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmPayment">Konfirmasi
                    & Simpan</button>
            </div>
        </div>
    </div>
</div>
