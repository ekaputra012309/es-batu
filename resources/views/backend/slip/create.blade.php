@extends('backend/template/app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Slip Gaji</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('slip.index') }}">Slip</a></li>
                            <li class="breadcrumb-item active">Add</li>
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
                                <h3 class="card-title">Tambah Slip Gaji</h3>
                            </div>
                            <form action="{{ route('slip.store') }}" method="POST">
                                @csrf
                                @auth
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                @endauth

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Tanggal</label>
                                                <input type="date" name="tanggal" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <div class="form-group">
                                                <label>Jumlah Hari Kerja</label>
                                                <input type="number" name="hari_kerja" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input type="text" name="nama" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Gaji Pokok</label>
                                                <input type="text" class="form-control currency-input" data-target="gp"
                                                    required>
                                                <input type="hidden" name="gp" id="gp">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Insentif</label>
                                                <input type="text" class="form-control currency-input"
                                                    data-target="inssentif" required>
                                                <input type="hidden" name="inssentif" id="inssentif">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Bonus</label>
                                                <input type="text" class="form-control currency-input"
                                                    data-target="bonus" required>
                                                <input type="hidden" name="bonus" id="bonus">
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Uang Makan</label>
                                                <input type="text" class="form-control currency-input"
                                                    data-target="uang_makan" required>
                                                <input type="hidden" name="uang_makan" id="uang_makan">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Potongan Uang Makan</label>
                                                <input type="text" class="form-control currency-input"
                                                    data-target="pot_uang_makan" required>
                                                <input type="hidden" name="pot_uang_makan" id="pot_uang_makan">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Potongan Kasbon</label>
                                                <input type="text" class="form-control currency-input"
                                                    data-target="pot_kasbon" required>
                                                <input type="hidden" name="pot_kasbon" id="pot_kasbon">
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Hutang</label>
                                                <input type="text" class="form-control currency-input"
                                                    data-target="hutang" required>
                                                <input type="hidden" name="hutang" id="hutang">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                    <a href="{{ route('slip.index') }}" class="btn btn-secondary">Back</a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <script>
            $(document).ready(function() {
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                });
            });
        </script>


    </div>
@endsection
