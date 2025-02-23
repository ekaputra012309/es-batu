@php
    $role = App\Models\Privilage::getRoleKodeForAuthenticatedUser();
@endphp

@extends('backend/template/app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Transaksi</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tambah Transaksi</h3>
                            <div class="card-tools">
                                <!-- @if(session('print_transaction_id') != 0)
                                    <button id="printButton" onclick="openTab()" class="btn btn-primary btn-sm d-none">
                                        <i class="fas fa-print"></i> Print Data
                                    </button>

                                    <script>
                                        function openTab() {
                                            const printTransactionId = @json(session('print_transaction_id'));
                                            const printUrl = "{{ url('transaksi/print') }}" + '/' + printTransactionId;
                                            window.open(printUrl, "_blank");
                                        }

                                        setTimeout(function() {
                                            document.getElementById('printButton').click();
                                        }, 3000);
                                    </script>
                                @endif -->

                            </div>
                        </div>
                        <form action="{{ route('transaksi.store') }}" method="POST">
                            @csrf
                            @auth
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            @endauth                            

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 class="mb-4">Transaksi Detail</h4>
                                        <div class="form-group">
                                            <label for="customer">Customer:</label>
                                            <input type="text" class="form-control" name="customer">
                                        </div>
                                        <div id="details">
                                            <div class="row mb-3 align-items-end">
                                                <div class="col-12 col-md-4">
                                                    <label for="berat">Berat (KG):</label>
                                                    <select name="details[0][berat]" class="form-control" required>
                                                        <option value="">Pilih Varian</option>
                                                        <option value="5">5 Kg</option>
                                                        <option value="10">10 Kg</option>
                                                        <option value="20">20 Kg</option> 
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label for="qty">Kuantiti (PCS):</label>
                                                    <input type="number" class="form-control qty" name="details[0][qty]" required>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label for="harga">Harga:</label>
                                                    <input type="tel" class="form-control price" name="details[0][harga]" required>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-secondary w-100 mt-2" id="addDetail">Tambah Detail</button>
                                    </div>

                                    <div class="col-md-4 text-center mt-4">
                                        <h3 class="mb-4">Total Bayar</h3>
                                        <div id="totalAmount" class="p-3 bg-light rounded">
                                            Rp <br> <span id="total" style="font-size: 40pt">0.00</span>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3 w-100">Simpan Transaksi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No Invoice</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Total</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datatransaksi as $transaksi)
                                    <tr>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ route('transaksi.print', $transaksi->id) }}" target="_blank">
                                                <i class="fas fa-print"></i> Print
                                            </a>
                                            @if (in_array($role, ['superadmin', 'admin']))
                                            <a class="btn btn-sm btn-danger" href="{{ route('transaksi.destroy', $transaksi->id) }}" data-confirm-delete="true">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                            @endif
                                        </td>
                                        <td>{{ $transaksi->no_inv }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d F Y, H:i') }}</td>
                                        <td>Rp {{ number_format($transaksi->total, 2, ',', '.') }}</td>
                                        <td>{{ $transaksi->user->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    </script>

    <script>
        $(document).ready(function() {
            let detailIndex = 1; // Start from 1 since we already have one detail

            // Function to calculate total amount
            function calculateTotal() {
                let total = 0;
                $('.form-row').each(function() {
                    const qty = $(this).find('.qty').val() || 0;
                    const price = parseInt($(this).find('.price').data('rawValue') || 0); // Get raw value for calculation
                    total += qty * price;
                    // total += price;
                });
                $('#total').text(total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })); // Update total amount
            }

            // Function to format price input
            function formatPriceInput(input) {
                const rawValue = input.val().replace(/\./g, ''); // Remove dots for raw value
                const formattedValue = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Format for display
                input.val(formattedValue); // Update input with formatted value
                input.data('rawValue', rawValue); // Store the raw value in data attribute
            }

            // Add initial event listener for the first detail
            $('.qty, .price').on('input', function() {
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
                        </div>
                        <input type="hidden" name="details[${detailIndex}][satuan]" value="Kg">
                        <div class="col-12 col-md-2 text-center text-md-start">
                            <br>
                            <button type="button" class="btn btn-danger removeDetail">Hapus</button>
                        </div>
                    </div>`;
                
                $('#details').append(newDetail);
                detailIndex++; // Increment index for next detail

                // Add event listeners for new input fields
                $('.qty:last, .price:last').on('input', function() {
                    if ($(this).hasClass('price')) {
                        formatPriceInput($(this));
                    }
                    calculateTotal();
                });

                calculateTotal(); // Recalculate total
            });

            $('#details').on('click', '.removeDetail', function() {
                $(this).closest('.detail-row').remove(); // Remove the parent row
                calculateTotal(); // Recalculate total after removal
            });

            // Form submission handler
            $('form').on('submit', function() {
                $('.price').each(function() {
                    const rawValue = $(this).data('rawValue');
                    $(this).val(rawValue); // Set the input value to the raw value for submission
                });
            });
        });
    </script>
</div>
@endsection
