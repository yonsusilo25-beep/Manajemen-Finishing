@extends('layouts.app')

@section('title', 'Edit Inventory')

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
    .stock-status {
        padding: 1rem;
        border-radius: 0.25rem;
        margin-bottom: 1.5rem;
    }
    .stock-status.warning {
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
    }
    .stock-status.danger {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Inventory</h3>
                <p class="text-subtitle text-muted">Update informasi inventori</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('department-inventories.index') }}">Inventories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                        <h4 class="card-title">
                            {{ $departmentInventory->department->name }} - {{ $departmentInventory->material->name }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Stock Status Warning -->
                        @if($departmentInventory->needsReorder())
                            <div class="stock-status danger">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                <strong>Perlu Reorder!</strong> Stok saat ini {{ $departmentInventory->current_stock }} {{ $departmentInventory->unit }},
                                sudah mencapai titik reorder ({{ $departmentInventory->reorder_point }}).
                            </div>
                        @elseif($departmentInventory->isBelowMinimum())
                            <div class="stock-status warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Stok Rendah!</strong> Stok saat ini di bawah minimum ({{ $departmentInventory->min_stock }}).
                            </div>
                        @endif

                        <div class="info-box">
                            <i class="bi bi-info-circle me-2"></i>
                            <small>Anda dapat mengedit parameter stok (min, max, reorder) tetapi tidak dapat mengubah department/material yang sudah tercatat.</small>
                        </div>

                        <form action="{{ route('department-inventories.update', $departmentInventory) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information (Read-only) -->
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-info-circle me-2"></i>Informasi Dasar
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Department</label>
                                    <input type="text" class="form-control" value="{{ $departmentInventory->department->name }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Material</label>
                                    <input type="text" class="form-control" value="{{ $departmentInventory->material->name }}" disabled>
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
                                           id="current_stock" name="current_stock"
                                           value="{{ old('current_stock', $departmentInventory->current_stock) }}" min="0" required>
                                    @error('current_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="unit" class="form-label required">Satuan</label>
                                    <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                           id="unit" name="unit" value="{{ old('unit', $departmentInventory->unit) }}"
                                           placeholder="kg, pcs, liter, dll" required>
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
                                           id="min_stock" name="min_stock"
                                           value="{{ old('min_stock', $departmentInventory->min_stock) }}" min="0">
                                    <small class="text-muted">Alert jika stok di bawah nilai ini</small>
                                    @error('min_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="max_stock" class="form-label">Stok Maksimal</label>
                                    <input type="number" class="form-control @error('max_stock') is-invalid @enderror"
                                           id="max_stock" name="max_stock"
                                           value="{{ old('max_stock', $departmentInventory->max_stock) }}" min="0">
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
                                           id="reorder_point" name="reorder_point"
                                           value="{{ old('reorder_point', $departmentInventory->reorder_point) }}" min="0">
                                    <small class="text-muted">Stok kapan perlu pemesanan ulang</small>
                                    @error('reorder_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="reorder_quantity" class="form-label">Jumlah Reorder</label>
                                    <input type="number" class="form-control @error('reorder_quantity') is-invalid @enderror"
                                           id="reorder_quantity" name="reorder_quantity"
                                           value="{{ old('reorder_quantity', $departmentInventory->reorder_quantity) }}" min="0">
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
                                           id="location" name="location" value="{{ old('location', $departmentInventory->location) }}"
                                           placeholder="Rak A1, Bin 5, dll">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label required">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', $departmentInventory->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $departmentInventory->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Metadata -->
                            <div class="row mt-3 pt-3 border-top">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-plus me-1"></i>
                                        <strong>Created:</strong> {{ $departmentInventory->created_at->format('d M Y, H:i') }}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        <strong>Last Updated:</strong> {{ $departmentInventory->updated_at->format('d M Y, H:i') }}
                                    </small>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('department-inventories.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Update Inventory
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
