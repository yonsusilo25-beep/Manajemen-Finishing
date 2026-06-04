@extends('layouts.app')

@section('title', 'Stock Movements')

@push('styles')
<style>
    .table-responsive {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .stat-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-left: 4px solid;
        margin-bottom: 1.5rem;
    }
    .stat-card.in {
        border-left-color: #198754;
    }
    .stat-card.out {
        border-left-color: #dc3545;
    }
    .stat-card.adjustment {
        border-left-color: #ffc107;
    }
    .stat-card.total {
        border-left-color: #0d6efd;
    }
    .stat-card .stat-icon {
        font-size: 2.5rem;
        opacity: 0.3;
    }
    .stat-card .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    .stat-card .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .filter-card {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .stock-change.positive {
        color: #198754;
        font-weight: 600;
    }
    .stock-change.negative {
        color: #dc3545;
        font-weight: 600;
    }
    .movement-icon {
        font-size: 1.25rem;
        margin-right: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Stock Movements History</h3>
                <p class="text-subtitle text-muted">Track all inventory movements</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Stock Movements</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card total">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-value text-primary">{{ number_format($stats['total_movements']) }}</div>
                            <div class="stat-label">Total Movements</div>
                        </div>
                        <div class="stat-icon text-primary">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card in">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-value text-success">{{ number_format($stats['stock_in_today'], 2) }}</div>
                            <div class="stat-label">Stock In Today</div>
                        </div>
                        <div class="stat-icon text-success">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card out">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-value text-danger">{{ number_format($stats['stock_out_today'], 2) }}</div>
                            <div class="stat-label">Stock Out Today</div>
                        </div>
                        <div class="stat-icon text-danger">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card adjustment">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-value text-warning">{{ $stats['adjustments_today'] }}</div>
                            <div class="stat-label">Adjustments Today</div>
                        </div>
                        <div class="stat-icon text-warning">
                            <i class="bi bi-arrows-angle-contract"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-card">
            <form method="GET" action="{{ route('stock-movements.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search Material</label>
                        <input type="text" class="form-control form-control-sm" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Material name or code">
                    </div>
                    <div class="col-md-2">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select form-select-sm" id="type" name="type">
                            <option value="">All Types</option>
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="material_id" class="form-label">Material</label>
                        <select class="form-select form-select-sm" id="material_id" name="material_id">
                            <option value="">All Materials</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>
                                    {{ $material->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Date From</label>
                        <input type="date" class="form-control form-control-sm" id="date_from" name="date_from"
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Date To</label>
                        <input type="date" class="form-control form-control-sm" id="date_to" name="date_to"
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                    </div>
                </div>
                @if(request()->hasAny(['search', 'type', 'material_id', 'date_from', 'date_to']))
                    <div class="row mt-2">
                        <div class="col-12">
                            <a href="{{ route('stock-movements.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Clear Filters
                            </a>
                        </div>
                    </div>
                @endif
            </form>
        </div>

        <!-- Movements Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Movement History</h5>
                <div>
                    <a href="{{ route('stock-movements.export') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="10%">Date</th>
                                <th width="10%">Type</th>
                                <th width="20%">Material</th>
                                <th width="10%" class="text-end">Quantity</th>
                                <th width="10%" class="text-end">Before</th>
                                <th width="10%" class="text-end">After</th>
                                <th width="10%" class="text-end">Change</th>
                                <th width="12%">User</th>
                                <th width="8%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $movement)
                                <tr>
                                    <td>
                                        <small>{{ $movement->movement_date->format('d M Y') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $movement->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $movement->type_badge_class }}">
                                            <i class="bi bi-{{ $movement->type_icon }} movement-icon"></i>
                                            {{ $movement->type_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $movement->material->code ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $movement->material->name ?? 'N/A' }}</small>
                                        @if($movement->material->category)
                                            <br>
                                            <span class="badge bg-light text-dark">{{ $movement->material->category->name }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-{{ $movement->type_badge_class }}">
                                            {{ $movement->formatted_quantity }}
                                        </strong>
                                        <br>
                                        <small class="text-muted">{{ $movement->material->unit ?? '' }}</small>
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format($movement->stock_before, 2) }}</strong>
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format($movement->stock_after, 2) }}</strong>
                                    </td>
                                    <td class="text-end">
                                        <span class="stock-change {{ $movement->stock_change >= 0 ? 'positive' : 'negative' }}">
                                            {{ $movement->stock_change >= 0 ? '+' : '' }}{{ number_format($movement->stock_change, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="bi bi-person me-1 text-muted"></i>
                                        <small>{{ $movement->user->name ?? 'System' }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('stock-movements.show', $movement) }}" class="btn btn-sm btn-info" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="text-muted mt-2">No stock movements found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $movements->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Set today as max date for date inputs
    document.addEventListener('DOMContentLoaded', function() {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        const today = new Date().toISOString().split('T')[0];
        dateInputs.forEach(input => {
            input.setAttribute('max', today);
        });
    });
</script>
@endpush
