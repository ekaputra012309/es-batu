<script>
    $(document).ready(function() {
        let detailIndex = 1; // Start from 1 since we already have one detail

        // Function to calculate total amount
        function calculateTotal() {
            let total = 0;
            $('.detail-row, #details .row.mb-3').each(function() { // Include both static & dynamic rows
                const qty = $(this).find('.qty').val() || 0;
                const price = parseFloat($(this).find('.price').val().replace(/\./g, '')) ||
                    0; // Convert formatted value to number
                total += qty * price;
            });
            $('#total').text(total.toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })); // Update total amount
        }

        // Function to format price input
        function formatPriceInput(input) {
            let rawValue = input.val().replace(/\D/g, ''); // Remove non-numeric characters
            let formattedValue = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Format with dots
            input.val(formattedValue);
        }

        // Add event listener for dynamically added fields
        $(document).on('input', '.qty, .price', function() {
            if ($(this).hasClass('price')) {
                formatPriceInput($(this));
            }
            calculateTotal();
        });

        $('#addDetail').on('click', function() {
            const newDetail = `
            <div class="row g-3 detail-row">
                <div class="col-12 col-md-4">
                    <label for="berat">Berat (KG):</label>
                    <select name="details[${detailIndex}][berat]" class="form-control w-100" required>
                        <option value="">Pilih Varian</option>
                        <option value="5">5 Kg</option>
                        <option value="10">10 Kg</option>
                        <option value="20">20 Kg</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label for="qty">Kuantiti:</label>
                    <input type="number" class="form-control qty w-100" name="details[${detailIndex}][qty]" required>
                </div>
                <div class="col-12 col-md-3">
                    <label for="harga">Harga:</label>
                    <input type="tel" class="form-control price w-100" name="details[${detailIndex}][harga]" required>
                    <input type="hidden" name="details[${detailIndex}][satuan]" value="Kg">
                </div>
                <div class="col-12 col-md-2 text-center text-md-start">
                    <br>
                    <button type="button" class="btn btn-danger removeDetail">Hapus</button>
                </div>
            </div>`;

            $('#details').append(newDetail);
            detailIndex++;
            calculateTotal();
        });

        $('#details').on('click', '.removeDetail', function() {
            $(this).closest('.detail-row').remove();
            calculateTotal();
        });

        $('form').on('submit', function() {
            $('.price').each(function() {
                $(this).val($(this).val().replace(/\./g,
                    '')); // Remove formatting before submission
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#triggerModal').click(function(e) {
            e.preventDefault();
            $('#paymentModal').modal('show');
        });

        $('#status').on('change', function() {
            const status = $(this).val();
            const totalText = $('#total').text().replace(/\./g, '').replace(',',
                '.'); // e.g., 25.000,00 → 25000.00
            const total = Math.floor(parseFloat(totalText)) || 0;

            if (status == "0") {
                $('#nominal').val(total).prop('readonly', true);
            } else {
                $('#nominal').val('').prop('readonly', false);
            }
        });

        $('#confirmPayment').click(function() {
            // Append hidden inputs to the form
            let nominalInput = $('#nominal').val();
            let nominal = parseInt(nominalInput.replace(/\./g, '').split(',')[0]) || 0;
            let status = $('#status').val();

            // if (!status || !nominal) {
            if (!status) {
                alert('Status dan nominal harus diisi!');
                return;
            }

            $('<input>').attr({
                type: 'hidden',
                name: 'status',
                value: status
            }).appendTo('form');

            $('<input>').attr({
                type: 'hidden',
                name: 'nominal',
                value: nominal
            }).appendTo('form');

            // $('form').submit();
            $('#paymentForm').submit();
        });
    });
</script>
