{{-- Script --}}
<script>
    const detailIndices = {}; // store indexes per transaksiId

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.add-detail-btn').forEach(button => {
            button.addEventListener('click', function() {
                const transaksiId = this.dataset.transaksiId;
                const container = document.getElementById(`detail-container-${transaksiId}`);

                // Initialize index for this transaksi if not exists
                if (!detailIndices[transaksiId]) {
                    detailIndices[transaksiId] = 0;
                }

                const index = detailIndices[transaksiId];

                const detailGroup = document.createElement('div');
                detailGroup.className = 'detail-group row mb-2';
                detailGroup.innerHTML = `
                    <div class="col-md-3">
                        <label>Berat</label>
                        <select name="details[${index}][berat]" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="5">5 Kg</option>
                            <option value="10">10 Kg</option>
                            <option value="20">20 Kg</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Qty</label>
                        <input type="number" name="details[${index}][qty]" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Harga</label>
                        <input type="number" name="details[${index}][harga]" class="form-control" required>
                        <input type="hidden" name="details[${index}][satuan]" value="Kg">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-detail">Hapus</button>
                    </div>
                `;

                container.appendChild(detailGroup);
                detailIndices[transaksiId]++;
            });
        });

        // Handle remove detail
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-detail')) {
                const group = e.target.closest('.detail-group');
                if (group) group.remove();
            }
        });
    });
</script>
