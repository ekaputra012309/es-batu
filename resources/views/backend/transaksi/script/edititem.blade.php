<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Initialize global index tracking per transaksi
        window.detailIndices = window.detailIndices || {};

        // Loop through all edit forms to set initial index
        document.querySelectorAll('.detail-edit-form').forEach(function(form) {
            const transaksiId = form.dataset.transaksiId;
            const container = document.getElementById(`detail-containerEdit-${transaksiId}`);
            const existingCount = container.querySelectorAll('.detail-group').length;
            window.detailIndices[transaksiId] = existingCount;
        });

        // 2. Add new detail row when 'Tambah Detail' clicked
        document.querySelectorAll('.add-detail-btn-edit').forEach(function(button) {
            button.addEventListener('click', function() {
                const transaksiId = this.dataset.transaksiId;
                const container = document.getElementById(
                    `detail-containerEdit-${transaksiId}`);

                const index = window.detailIndices[transaksiId] || 0;
                window.detailIndices[transaksiId]++;

                const newGroup = document.createElement('div');
                newGroup.classList.add('detail-group', 'row', 'mb-2');
                newGroup.innerHTML = `
                    <div class="col-md-3">
                        <label>Tanggal</label>
                        <input type="date" name="details[${index}][tanggal]" class="form-control" required>
                    </div>
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
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-detail-edit"> <i class="fas fa-trash"></i></button>
                    </div>
                `;
                container.appendChild(newGroup);
            });
        });

        // 3. Optional: remove row (new or existing)
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-detail-edit');
            if (btn) {
                const row = btn.closest('.detail-group');

                // Check if this row has an ID (existing data)
                const idInput = row.querySelector('input[name*="[id]"]');
                if (idInput) {
                    // Mark for deletion
                    const namePrefix = idInput.name.replace('[id]', '');
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = `${namePrefix}[_delete]`;
                    deleteInput.value = '1';
                    row.appendChild(deleteInput);

                    // Hide instead of removing
                    row.style.display = 'none';
                } else {
                    // Unsaved new row — remove immediately
                    row.remove();
                }
            }
        });

    });
</script>
