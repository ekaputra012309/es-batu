@extends('backend/template/app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Slip Gaji</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('slip.index') }}">Slip</a></li>
                            <li class="breadcrumb-item active">Edit</li>
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
                                <h3 class="card-title">Edit Slip Gaji</h3>
                            </div>
                            <form action="{{ route('slip.update', $slip->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="user_id" value="{{ $slip->user_id }}">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Tanggal</label>
                                                <input type="date" name="tanggal" class="form-control"
                                                    value="{{ old('tanggal', \Carbon\Carbon::parse($slip->tanggal)->format('Y-m-d')) }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <div class="form-group">
                                                <label>Jumlah Hari Kerja</label>
                                                <input type="number" name="hari_kerja" class="form-control"
                                                    value="{{ old('hari_kerja', $slip->hari_kerja) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input type="text" name="nama" class="form-control"
                                                    value="{{ old('nama', $slip->nama) }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Gaji Pokok</label>
                                                <input type="number" name="gp" class="form-control"
                                                    value="{{ old('gp', $slip->gp) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Insentif</label>
                                                <input type="number" name="inssentif" class="form-control"
                                                    value="{{ old('inssentif', $slip->inssentif) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Bonus</label>
                                                <input type="number" name="bonus" class="form-control"
                                                    value="{{ old('bonus', $slip->bonus) }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Uang Makan</label>
                                                <input type="number" name="uang_makan" class="form-control"
                                                    value="{{ old('uang_makan', $slip->uang_makan) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Potongan Uang Makan</label>
                                                <input type="number" name="pot_uang_makan" class="form-control"
                                                    value="{{ old('pot_uang_makan', $slip->pot_uang_makan) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Potongan Kasbon</label>
                                                <input type="number" name="pot_kasbon" class="form-control"
                                                    value="{{ old('pot_kasbon', $slip->pot_kasbon) }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Hutang</label>
                                                <input type="number" name="hutang" class="form-control"
                                                    value="{{ old('hutang', $slip->hutang) }}" required>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('slip.index') }}" class="btn btn-secondary">Back</a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
