@extends('layouts.app')

@section('title', 'Materials')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Materials Inventory</h2>
    @if(auth()->user()->isWarehouse() || auth()->user()->isAdmin())
    <a href="{{ route('materials.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Material
    </a>
    @endif
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('materials.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search materials..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="Part" {{ request('type') == 'Part' ? 'selected' : '' }}>Part</option>
                    <option value="Material" {{ request('type') == 'Material' ? 'selected' : '' }}>Material</option>
                    <option value="Consumable" {{ request('type') == 'Consumable' ? 'selected' : '' }}>Consumable</option>
                    <option value="Tool" {{ request('type') == 'Tool' ? 'selected' : '' }}>Tool</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="low_stock" value="1" id="lowStock" {{ request('low_stock') ? 'checked' : '' }}>
                    <label class="form-check-label" for="lowStock">
                        Low Stock Only
                    </label>
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Materials Grid -->
<div class="row row-cols-1 row-cols-md-3 g-4">
    @forelse($materials as $material)
    <div class="col">
        <div class="card h-100">
            @if($material->image)
            <img src="{{ Storage::url($material->image) }}" class="card-img-top" alt="{{ $material->name }}" style="height: 200px; object-fit: cover;">
            @else
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                <i class="bi bi-box-seam" style="font-size: 4rem; color: #dee2e6;"></i>
            </div>
            @endif
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0">{{ $material->name }}</h5>
                    <span class="badge bg-info">{{ $material->type }}</span>
                </div>
                <p class="text-muted small mb-2">{{ $material->code }}</p>

                @if($material->description)
                <p class="card-text small">{{ Str::limit($material->description, 80) }}</p>
                @endif

                <div class="mb-2">
                    <label class="small text-muted">Current Stock</label>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="{{ $material->isLowStock() ? 'text-danger' : '' }}">
                            {{ $material->current_stock }} {{ $material->unit }}
                        </strong>
                        @if($material->isLowStock())
                            <span class="badge bg-danger">Low Stock</span>
                        @endif
                    </div>
                    <div class="progress mt-1" style="height: 5px;">
                        <div class="progress-bar {{ $material->isLowStock() ? 'bg-danger' : 'bg-success' }}"
                             style="width: {{ min($material->stockPercentage(), 100) }}%"></div>
                    </div>
                    <small class="text-muted">Min: {{ $material->min_stock }} | Max: {{ $material->max_stock }}</small>
                </div>

            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('materials.show', $material) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i> View Details
                </a>
                @if(auth()->user()->isWarehouse() || auth()->user()->isAdmin())
                <a href="{{ route('materials.edit', $material) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            No materials found.
        </div>
    </div>
    @endforelse
</div>

@if($materials->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $materials->links() }}
</div>
@endif
@endsection
