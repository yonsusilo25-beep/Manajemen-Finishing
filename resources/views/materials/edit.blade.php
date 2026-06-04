{{-- Include layout utama --}}
@extends('layouts.app')

{{-- Set title --}}
@section('title', 'Edit Material')

{{-- Isi content --}}
@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Material</h3>
                    <p class="text-subtitle text-muted">Update material information</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('materials.index') }}">Materials</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <form action="{{ route('materials.update', $material) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Basic Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="code">Material Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('code') is-invalid @enderror"
                                                id="code" name="code" value="{{ old('code', $material->code) }}"
                                                placeholder="e.g., WAX-001" required>
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Material Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name', $material->name) }}"
                                                placeholder="e.g., Wax Material A" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Type <span class="text-danger">*</span></label>
                                            <select class="form-select @error('type') is-invalid @enderror" id="type"
                                                name="type" required>
                                                <option value="">Select Type</option>
                                                <option value="Part"
                                                    {{ old('type', $material->type) == 'Part' ? 'selected' : '' }}>Part
                                                </option>
                                                <option value="Material"
                                                    {{ old('type', $material->type) == 'Material' ? 'selected' : '' }}>
                                                    Material</option>
                                                <option value="Consumable"
                                                    {{ old('type', $material->type) == 'Consumable' ? 'selected' : '' }}>
                                                    Consumable</option>
                                                <option value="Tool"
                                                    {{ old('type', $material->type) == 'Tool' ? 'selected' : '' }}>Tool
                                                </option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="supplier">Supplier</label>
                                            <input type="text"
                                                class="form-control @error('supplier') is-invalid @enderror" id="supplier"
                                                name="supplier" value="{{ old('supplier', $material->supplier) }}"
                                                placeholder="e.g., PT. Supplier ABC">
                                            @error('supplier')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="3" placeholder="Material description...">{{ old('description', $material->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Unit & Pricing -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Unit & Pricing</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="unit">Unit <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                                id="unit" name="unit" value="{{ old('unit', $material->unit) }}"
                                                placeholder="e.g., kg, pcs, liter" required>
                                            @error('unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="alternative_unit">Alternative Unit</label>
                                            <input type="text"
                                                class="form-control @error('alternative_unit') is-invalid @enderror"
                                                id="alternative_unit" name="alternative_unit"
                                                value="{{ old('alternative_unit', $material->alternative_unit) }}"
                                                placeholder="e.g., gram, box">
                                            @error('alternative_unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="conversion_factor">Conversion Factor</label>
                                            <input type="number" step="0.0001"
                                                class="form-control @error('conversion_factor') is-invalid @enderror"
                                                id="conversion_factor" name="conversion_factor"
                                                value="{{ old('conversion_factor', $material->conversion_factor) }}"
                                                placeholder="e.g., 1000">
                                            @error('conversion_factor')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">1 primary unit = ? alternative units</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="unit_price">Unit Price (Rp) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('unit_price') is-invalid @enderror"
                                                id="unit_price" name="unit_price"
                                                value="{{ old('unit_price', $material->unit_price) }}" placeholder="0"
                                                required>
                                            @error('unit_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lead_time_days">Lead Time (Days)</label>
                                            <input type="number"
                                                class="form-control @error('lead_time_days') is-invalid @enderror"
                                                id="lead_time_days" name="lead_time_days"
                                                value="{{ old('lead_time_days', $material->lead_time_days) }}"
                                                placeholder="0">
                                            @error('lead_time_days')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Information -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Stock Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="current_stock">Current Stock <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('current_stock') is-invalid @enderror"
                                                id="current_stock" name="current_stock"
                                                value="{{ old('current_stock', $material->current_stock) }}"
                                                placeholder="0" required>
                                            @error('current_stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="min_stock">Minimum Stock <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('min_stock') is-invalid @enderror"
                                                id="min_stock" name="min_stock"
                                                value="{{ old('min_stock', $material->min_stock) }}" placeholder="0"
                                                required>
                                            @error('min_stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="max_stock">Maximum Stock <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('max_stock') is-invalid @enderror"
                                                id="max_stock" name="max_stock"
                                                value="{{ old('max_stock', $material->max_stock) }}" placeholder="0"
                                                required>
                                            @error('max_stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="packingUnit">Packing Unit <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('packing_unit') is-invalid @enderror"
                                                id="packing_unit" name="packing_unit"
                                                value="{{ old('packing_unit', $material->packing_unit) }}" placeholder="Pack/Drum/Box"
                                                required>
                                            @error('packing_unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Number of primary units per packing</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="capacityPerPack">Capacity Per Pack</label>
                                            <input type="number" step="0.0001"
                                                class="form-control @error('capacity_per_pack') is-invalid @enderror"
                                                id="capacity_per_pack" name="capacity_per_pack"
                                                value="{{ old('capacity_per_pack', $material->capacity_per_pack) }}" placeholder="e.g., 5.5">
                                            @error('capacity_per_pack')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">e.g., 5.5 kg per pack</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="storage_location">Storage Location</label>
                                    <input type="text"
                                        class="form-control @error('storage_location') is-invalid @enderror"
                                        id="storage_location" name="storage_location"
                                        value="{{ old('storage_location', $material->storage_location) }}"
                                        placeholder="e.g., Warehouse A-01">
                                    @error('storage_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Specifications -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Specifications</h4>
                            </div>
                            <div class="card-body">
                                <div id="specifications-container">
                                    @forelse($material->specifications as $index => $spec)
                                        <div class="specification-item mb-3">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control"
                                                        name="specifications[{{ $index }}][name]"
                                                        value="{{ $spec->spec_name }}" placeholder="Specification Name">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control"
                                                        name="specifications[{{ $index }}][value]"
                                                        value="{{ $spec->spec_value }}" placeholder="Value">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-danger btn-sm remove-spec">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="specification-item mb-3">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control"
                                                        name="specifications[0][name]" placeholder="Specification Name">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control"
                                                        name="specifications[0][value]" placeholder="Value">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-danger btn-sm remove-spec"
                                                        disabled>
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" id="add-specification">
                                    <i class="bi bi-plus-circle"></i> Add Specification
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Image Upload -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Material Image</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="mb-3">
                                        @if ($material->image)
                                            <img id="image-preview" src="{{ Storage::url($material->image) }}"
                                                alt="Material Image" class="img-fluid rounded"
                                                style="max-height: 300px; width: 100%; object-fit: cover;">
                                        @else
                                            <img id="image-preview"
                                                src="{{ asset('assets/compiled/svg/placeholder.svg') }}" alt="Preview"
                                                class="img-fluid rounded"
                                                style="max-height: 300px; width: 100%; object-fit: cover;">
                                        @endif
                                    </div>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Max size: 2MB. Leave empty to keep current image.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Status</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $material->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                                <small class="text-muted">Inactive materials won't be available for requests</small>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary btn-block mb-2">
                                    <i class="bi bi-save"></i> Update Material
                                </button>
                                <a href="{{ route('materials.show', $material) }}"
                                    class="btn btn-secondary btn-block mb-2">
                                    <i class="bi bi-eye"></i> View Material
                                </a>
                                <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary btn-block">
                                    <i class="bi bi-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>

@endsection

@push('scripts')
    <script>
        // Image Preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Specifications Dynamic Fields
        let specCount = {{ $material->specifications->count() > 0 ? $material->specifications->count() : 1 }};
        document.getElementById('add-specification').addEventListener('click', function() {
            const container = document.getElementById('specifications-container');
            const newSpec = `
            <div class="specification-item mb-3">
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" class="form-control"
                               name="specifications[${specCount}][name]"
                               placeholder="Specification Name">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control"
                               name="specifications[${specCount}][value]"
                               placeholder="Value">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-spec">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', newSpec);
            specCount++;

            attachRemoveHandlers();
        });

        function attachRemoveHandlers() {
            document.querySelectorAll('.remove-spec').forEach(button => {
                button.onclick = function() {
                    if (!this.disabled) {
                        this.closest('.specification-item').remove();
                    }
                };
            });
        }

        attachRemoveHandlers();
    </script>
@endpush
