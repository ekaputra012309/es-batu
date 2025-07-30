{{-- script for add item --}}
<script>
    $(document).ready(function() {
        let modalIndex = {};

        // Unbind previous to prevent duplicate firing
        $('.addModalDetail').off('click').on('click', function() {
            const id = $(this).data('id');

            if (!modalIndex[id]) modalIndex[id] = 1;

            const html = `
            <div class="row mb-3 align-items-end detail-row">
                <div class="col-md-4">
                    <select name="details[${modalIndex[id]}][berat]" class="form-control" required>
                        <option value="5">5 Kg</option>
                        <option value="10">10 Kg</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="details[${modalIndex[id]}][qty]" class="form-control" required placeholder="Qty">
                </div>
                <div class="col-md-3">
                    <input type="number" name="details[${modalIndex[id]}][harga]" class="form-control" required placeholder="Harga">
                    <input type="hidden" name="details[${modalIndex[id]}][satuan]" value="Kg">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm removeDetail">Hapus</button>
                </div>
            </div>`;

            $(`#modalDetails${id}`).append(html);
            modalIndex[id]++;
        });

        // Remove row
        $(document).on('click', '.removeDetail', function() {
            $(this).closest('.detail-row').remove();
        });
    });
</script>
