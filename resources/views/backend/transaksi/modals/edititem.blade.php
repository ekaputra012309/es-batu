@php use Carbon\Carbon; @endphp

{{-- Modal Edit Item Detail --}}
<div class="modal fade" id="editItemModal{{ $transaksi->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editItemModalLabel{{ $transaksi->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('transaksi.detail.update', $transaksi->id) }}" method="POST"
                    class="detail-edit-form" data-transaksi-id="{{ $transaksi->id }}">
                    @csrf
                    @method('PUT')

                    @auth
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    @endauth

                    <div id="detail-containerEdit-{{ $transaksi->id }}">
                        @foreach ($transaksi->details as $index => $detail)
                            <div class="detail-group row mb-2" data-detail-id="{{ $detail->id }}">
                                <input type="hidden" name="details[{{ $index }}][id]"
                                    value="{{ $detail->id }}">
                                <div class="col-md-3">
                                    <label>Tanggal</label>
                                    <input type="date" name="details[{{ $index }}][tanggal]"
                                        class="form-control"
                                        value="{{ \Carbon\Carbon::parse($detail->tanggal)->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <label>Berat</label>
                                    <select name="details[{{ $index }}][berat]" class="form-control" required>
                                        <option value="">Pilih</option>
                                        <option value="5" @selected($detail->berat == 5)>5 Kg</option>
                                        <option value="10" @selected($detail->berat == 10)>10 Kg</option>
                                        <option value="20" @selected($detail->berat == 20)>20 Kg</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Qty</label>
                                    <input type="number" name="details[{{ $index }}][qty]"
                                        value="{{ $detail->qty }}" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label>Harga</label>
                                    <input type="number" name="details[{{ $index }}][harga]"
                                        value="{{ $detail->harga }}" class="form-control" required>
                                    <input type="hidden" name="details[{{ $index }}][satuan]" value="Kg">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-detail-edit"
                                        data-id="{{ $detail->id }}"> <i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-sm btn-secondary mb-2 add-detail-btn-edit"
                        data-transaksi-id="{{ $transaksi->id }}">+ Tambah Detail</button>
                    <br>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
