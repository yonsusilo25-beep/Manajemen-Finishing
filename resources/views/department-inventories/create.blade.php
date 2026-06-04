@extends('layouts.app')

@section('title', 'Create Inventory')

@push('styles')
<style>
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .required::after {
        content: " *";
        color: red;
    }
    .card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    }
    .info-box {
        background-color: #e7f3ff;
        border-left: 4px solid #0066cc;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 0.25rem;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Add New Inventory</h3>
                <p class="text-subtitle text-muted">Tambahkan inventori baru ke department</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('department-inventories.index') }}">Inventories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Inventory Information</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('department-inventories.store') }}" method="POST">
                            @csrf

                            <div class="info-box">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>Setiap kombinasi Department + Material hanya bisa ada satu entry</small>
                            </div>

                            <!-- Basic Information -->
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-info-circle me-2"></i>Informasi Dasar
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="department_id" class="form-label required">Department</label>
                                    <select class="form-select @error('department_id') is-invalid @enderror"
                                            id="department_id" name="department_id" required>
                                        <option value="">Pilih Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="material_id" class="form-label required">Material</label>
                                    <select class="form-select @error('material_id') is-invalid @enderror"
                                            id="material_id" name="material_id" required>
                                        <option value="">Pilih Material</option>
                                        @foreach($materials as $material)
                                            <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                                {{ $material->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('material_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Stock Information -->
                            <h6 class="text-muted mb-3 mt-4">
                                <i class="bi bi-box me-2"></i>Informasi Stok
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="current_stock" class="form-label required">Stok Saat Ini</label>
                                    <input type="number" class="form-control @error('current_stock') is-invalid @enderror"
                                           id="current_stock" name="current_stock" value="{{ old('current_stock', 0) }}"
                                           min="0" required>
                                    @error('current_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="unit" class="form-label required">Satuan</label>
                                    <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                           id="unit" name="unit" value="{{ old('unit', 'kg') }}" placeholder="kg, pcs, liter, dll" required>
                                    @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Stock Management -->
                            <h6 class="text-muted mb-3 mt-4">
                                <i class="bi bi-graph-up me-2"></i>Manajemen Stok
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="min_stock" class="form-label">Stok Minimum</label>
                                    <input type="number" class="form-control @error('min_stock') is-invalid @enderror"
                                           id="min_stock" name="min_stock" value="{{ old('min_stock') }}" min="0">
                                    <small class="text-muted">Alert jika stok di bawah nilai ini</small>
                                    @error('min_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="max_stock" class="form-label">Stok Maksimal</label>
                                    <input type="number" class="form-control @error('max_stock') is-invalid @enderror"
                                           id="max_stock" name="max_stock" value="{{ old('max_stock') }}" min="0">
                                    <small class="text-muted">Kapasitas penyimpanan maksimal</small>
                                    @error('max_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="reorder_point" class="form-label">Titik Reorder</label>
                                    <input type="number" class="form-control @error('reorder_point') is-invalid @enderror"
                                           id="reorder_point" name="reorder_point" value="{{ old('reorder_point') }}" min="0">
                                    <small class="text-muted">Stok kapan perlu pemesanan ulang</small>
                                    @error('reorder_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="reorder_quantity" class="form-label">Jumlah Reorder</label>
                                    <input type="number" class="form-control @error('reorder_quantity') is-invalid @enderror"
                                           id="reorder_quantity" name="reorder_quantity" value="{{ old('reorder_quantity') }}" min="0">
                                    <small class="text-muted">Jumlah standar pemesanan</small>
                                    @error('reorder_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Location & Status -->
                            <h6 class="text-muted mb-3 mt-4">
                                <i class="bi bi-geo-alt me-2"></i>Lokasi & Status
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">Lokasi Penyimpanan</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror"
                                           id="location" name="location" value="{{ old('location') }}"
                                           placeholder="Rak A1, Bin 5, dll">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label required">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('department-inventories.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Create Inventory
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
