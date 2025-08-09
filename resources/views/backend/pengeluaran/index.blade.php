@extends('backend/template/app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Pengeluaran</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> -->
                            {{-- <li class="breadcrumb-item"><a href="#">Layout</a></li> --}}
                            <li class="breadcrumb-item active">Pengeluaran</li>
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
                                <form id="pengeluaranForm" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="POST">
                                    <!-- Dynamically changed to PUT if needed -->
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" id="pengeluaran_id">

                                    <!-- Tanggal -->
                                    <div class="col-md-3 mb-3">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" class="form-control" name="tanggal" id="tanggal" required>
                                    </div>

                                    <!-- Dynamic Detail Section -->
                                    <div id="details"></div>
                                    <button type="button" class="btn btn-secondary" id="addDetail">Tambah Detail</button>

                                    <div class="mt-3 d-flex">
                                        <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                                        <button type="reset" class="btn btn-secondary" onclick="location.reload();">
                                            Batal
                                        </button>
                                    </div>
                                </form>

                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>Nominal</th>
                                            <th>Total</th>
                                            <th>User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datapengeluaran as $pengeluaran)
                                            <tr>
                                                <td>
                                                    <a class="btn btn-sm btn-primary editBtn"
                                                        data-id="{{ $pengeluaran->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>

                                                    <a class="btn btn-sm btn-danger"
                                                        href="{{ route('pengeluaran.destroy', $pengeluaran->id) }}"
                                                        data-confirm-delete="true">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($pengeluaran->tanggal)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>
                                                    <ul class="mb-0 ps-3">
                                                        @foreach ($pengeluaran->details as $detail)
                                                            <li>
                                                                {{ $detail->keterangan }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul class="mb-0 ps-3">
                                                        @foreach ($pengeluaran->details as $detail)
                                                            <li>
                                                                <strong>Rp{{ number_format($detail->nominal, 0, ',', '.') }}</strong>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td>
                                                    Rp{{ number_format($pengeluaran->details->sum('nominal'), 0, ',', '.') }}
                                                </td>
                                                <td>{{ $pengeluaran->user->name }}</td>
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
                paging: false,
                lengthChange: false,
                autoWidth: false,
                responsive: false, // optional
                scrollY: false,
                scrollX: true
                // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        </script>

        <script>
            let detailIndex = 0;

            function resetForm() {
                $('#pengeluaranForm').trigger('reset');
                $('#details').html('');
                detailIndex = 0;
                $('input[name=_method]').val('POST');
                $('#pengeluaranForm').attr('action', "{{ route('pengeluaran.store') }}");
            }

            $('#addDetail').on('click', function() {
                const row = `
                    <div class="row mb-2 detail-row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="details[${detailIndex}][keterangan]" placeholder="Keterangan" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control nominal-input" placeholder="Nominal" required>
                            <input type="hidden" name="details[${detailIndex}][nominal]" class="nominal-hidden">
                        </div>
                        
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-remove">X</button>
                        </div>
                    </div>
                `;
                $('#details').append(row);
                detailIndex++;
            });

            $(document).on('click', '.btn-remove', function() {
                $(this).closest('.detail-row').remove();
            });

            const editUrl = @json(route('pengeluaran.edit', ['pengeluaran' => 'ID_REPLACE']));
            const updateUrl = @json(route('pengeluaran.update', ['pengeluaran' => 'ID_REPLACE']));

            $('.editBtn').on('click', function() {
                const id = $(this).data('id');
                const url = editUrl.replace('ID_REPLACE', id);
                const actionUrl = updateUrl.replace('ID_REPLACE', id);

                $.get(url, function(res) {
                    $('#pengeluaranForm').attr('action', actionUrl);
                    $('input[name=_method]').val('PUT');

                    $('#tanggal').val(res.pengeluaran.tanggal);
                    $('#pengeluaran_id').val(id);

                    $('#details').html('');
                    detailIndex = 0;

                    res.pengeluaran.details.forEach(detail => {
                        const row = `
                            <div class="row mb-2 detail-row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="details[${detailIndex}][keterangan]" placeholder="Keterangan" value="${detail.keterangan}" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control nominal-input" placeholder="Nominal" value="${formatRupiah(String(detail.nominal))}" required>
                                    <input type="hidden" name="details[${detailIndex}][nominal]" class="nominal-hidden" value="${detail.nominal}">
                                </div>
                                
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-remove">X</button>
                                </div>
                            </div>
                        `;
                        $('#details').append(row);
                        detailIndex++;
                    });
                });
            });
        </script>

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

    </div>
@endsection
