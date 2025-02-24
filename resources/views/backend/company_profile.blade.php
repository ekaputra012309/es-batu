@extends('backend.template.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Profil Perusahaan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Edit Profil Perusahaan</li>
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
                            <h3 class="card-title">Edit Profil Perusahaan</h3>
                        </div>
                        <form action="{{ route('companyProfile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <div class="row">
                                    <!-- Profile Image Section -->
                                    <div class="col-md-4 text-center">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Logo Perusahaan</label>
                                            <div class="d-flex justify-content-center">
                                                @if($companyProfile->image)
                                                    <img src="{{ asset($companyProfile->image) }}" alt="Company Image" class="img-thumbnail mb-3" width="150" height="150">
                                                @else
                                                    <img src="{{ asset('default-profile.png') }}" alt="Default Image" class="img-thumbnail mb-3" width="150" height="150">
                                                @endif
                                            </div>
                                            <input type="file" class="form-control" id="image" name="image">
                                        </div>
                                    </div>

                                    <!-- Profile Details Section -->
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Nama Perusahaan</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $companyProfile->name }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="address" class="form-label">Alamat</label>
                                            <input type="text" class="form-control" id="address" name="address" value="{{ $companyProfile->address }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ $companyProfile->phone }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $companyProfile->email }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="website" class="form-label">Website</label>
                                            <input type="url" class="form-control" id="website" name="website" value="{{ $companyProfile->website }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="logo" class="form-label">On/Off Sidebar Logo</label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="logo" name="logo" value="1" 
                                                    {{ $companyProfile->logo == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="logo">Enable Logo</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="description" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="description" name="description" rows="3" required>{{ $companyProfile->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection