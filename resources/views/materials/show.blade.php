{{-- Include layout utama --}}
@extends('layouts.app')

{{-- Set title --}}
@section('title', 'Material Details')

{{-- Isi content --}}
@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Material Details</h3>
                <p class="text-subtitle text-muted">View material information</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('materials.index') }}">Materials</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $material->code }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-8">
                <!-- Basic Information -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Basic Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-0">Material Code</h6>
                            </div>
                            <div class="col-md-9">
                                <p class="fw-bold mb-0">{{ $material->code }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-0">Material Name</h6>
                            </div>
                            <div class="col-md-9">
                                <p class="fw-bold mb-0">{{ $material->name }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-0">Type</h6>
                            </div>
                            <div class="col-md-9">
                                <span class="badge bg-info">{{ $material->type }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-0">Status</h6>
                            </div>
                            <div class="col-md-9">
                                @if($material->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                        </div>
                        @if($material->description)
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-0">Description</h6>
                            </div>
                            <div class="col-md-9">
                                <p class="mb-0">{{ $material->description }}</p>
                            </div>
                        </div>
                        @endif
                        @if($material->supplier)
                        <div class="row">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-0">Supplier</h6>
                            </div>
                            <div class="col-md-9">
                                <p class="mb-0">{{ $material->supplier }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Unit & Pricing -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Unit & Pricing</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-0">Primary Unit</h6>
                            </div>
                            <div class="col-md-9">
                                <p class="mb-0">{{ $material->unit }}</p>
                            </div>
                        </div>
                        @if($material->alternative_unit)
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-0">Alternative Unit</h6>
                            </div>
                            <div class="col-md-9">
                                <p class="mb-0">
                                    {{ $material->alternative_unit }}
                                    @if($material->conversion_factor)
                                        <small class="text-muted">(1 {{ $material->unit }} = {{ $material->conversion_factor }} {{ $material->alternative_unit }})</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-0">Unit Price</h6>
                            </div>
                            <div class="col-md-9">
                                <p class="mb-0 fw-bold">Rp {{ number_format($material->unit_price, 2, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-0">Lead Time</h6>
                            </div>
                            <div class="col-md-9">
                                <p class="mb-0">{{ $material->lead_time_days }} days</p>
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
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-0">Current Stock</h6>
                            </div>
                            <div class="col-md-9">
                                <h4 class="mb-0 {{ $material->isLowStock() ? 'text-danger' : 'text-success' }}">
                                    {{ $material->current_stock }} {{ $material->unit }}
                                    @if($material->isLowStock())
                                        <span class="badge bg-danger">Low Stock</span>
                                    @endif
                                    <span class="text-muted">({{ ($material->current_stock/$material->capacity_per_pack) }}) {{ $material->packing_unit }}</span>
                                </h4>
                                <div class="progress mt-2" style="height: 8px;">
                                    <div class="progress-bar {{ $material->isLowStock() ? 'bg-danger' : 'bg-success' }}"
                                         role="progressbar"
                                         style="width: {{ min($material->stockPercentage(), 100) }}%"
                                         aria-valuenow="{{ $material->stockPercentage() }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-0">Stock Range</h6>
                            </div>
                            <div class="col-md-9">
                                <p class="mb-0">
                                    Min: <span class="fw-bold">{{ $material->min_stock }} {{ $material->unit }}</span> |
                                    Max: <span class="fw-bold">{{ $material->max_stock }} {{ $material->unit }}</span>
                                </p>
                            </div>
                        </div>
                        @if($material->capacity_per_pack && $material->packing_unit)
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <h6 class="text-muted mb-0">Packing Capacity</h6>
                                </div>
                                <div class="col-md-9">
                                    <p class="mb-0">{{ $material->capacity_per_pack }}{{ $material->unit }}/{{ $material->packing_unit }} </p>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($material->storage_location)
                        <div class="row">
                            <div class="col-md-3">
                                <h6 class="text-muted mb-0">Storage Location</h6>
                            </div>
                            <div class="col-md-9">
                                <p class="mb-0">
                                    <i class="bi bi-geo-alt"></i> {{ $material->storage_location }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Specifications -->
                @if($material->specifications->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Technical Specifications</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    @foreach($material->specifications as $spec)
                                    <tr>
                                        <td class="text-muted" style="width: 40%">{{ $spec->spec_name }}</td>
                                        <td class="fw-bold">{{ $spec->spec_value }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Stock Movement History -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Recent Stock Movements</h4>
                    </div>
                    <div class="card-body">
                        @if($material->stockMovements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Stock After</th>
                                        <th>User</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($material->stockMovements->take(10) as $movement)
                                    <tr>
                                        <td>{{ $movement->movement_date->format('d M Y') }}</td>
                                        <td>
                                            @if($movement->type == 'in')
                                                <span class="badge bg-success">In</span>
                                            @elseif($movement->type == 'out')
                                                <span class="badge bg-danger">Out</span>
                                            @else
                                                <span class="badge bg-warning">Adjustment</span>
                                            @endif
                                        </td>
                                        <td>{{ $movement->quantity }} {{ $material->unit }}</td>
                                        <td>{{ $movement->stock_after }} {{ $material->unit }}</td>
                                        <td>{{ $movement->user->name }}</td>
                                        <td>{{ $movement->notes ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-center text-muted py-4">No stock movements recorded yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Material Image -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Material Image</h4>
                    </div>
                    <div class="card-body">
                        @if($material->image)
                            <img src="{{ Storage::url($material->image) }}"
                                 alt="{{ $material->name }}"
                                 class="img-fluid rounded">
                        @else
                            <div class="text-center py-5 bg-light rounded">
                                <i class="bi bi-box-seam" style="font-size: 4rem; color: #dee2e6;"></i>
                                <p class="text-muted mt-2">No image available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Quick Stats</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Stock Value</small>
                            <h5 class="mb-0">Rp {{ number_format($material->current_stock * $material->unit_price, 2, ',', '.') }}</h5>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Total Movements</small>
                            <h5 class="mb-0">{{ $material->stockMovements->count() }}</h5>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Created</small>
                            <h6 class="mb-0">{{ $material->created_at->format('d M Y') }}</h6>
                        </div>
                        <div>
                            <small class="text-muted">Last Updated</small>
                            <h6 class="mb-0">{{ $material->updated_at->format('d M Y H:i') }}</h6>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Actions</h4>
                    </div>
                    <div class="card-body">
                        @if(auth()->user()->isWarehouse() || auth()->user()->isAdmin())
                        <a href="{{ route('materials.edit', $material) }}" class="btn btn-primary btn-block mb-2">
                            <i class="bi bi-pencil"></i> Edit Material
                        </a>
                        @endif
                        <a href="{{ route('materials.index') }}" class="btn btn-secondary btn-block mb-2">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                        @if(auth()->user()->isWarehouse() || auth()->user()->isAdmin())
                        <button type="button" class="btn btn-danger btn-block" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i> Delete Material
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $material->name }}</strong>?</p>
                <p class="text-danger">This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('materials.destroy', $material) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
