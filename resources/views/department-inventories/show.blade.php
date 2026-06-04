@extends('layouts.app')

@section('title', 'Inventory Detail')

@push('styles')
<style>
    .detail-card {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-weight: 500;
        color: #666;
    }
    .detail-value {
        font-weight: 600;
        color: #333;
    }
    .badge-status {
        padding: 0.5rem 0.75rem;
        border-radius: 0.25rem;
    }
    .badge-active {
        background-color: #d4edda;
        color: #155724;
    }
    .badge-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }
    .status-warning {
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        padding: 1rem;
        border-radius: 0.25rem;
        margin-bottom: 1.5rem;
    }
    .status-danger {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        padding: 1rem;
        border-radius: 0.25rem;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Inventory Detail</h3>
                <p class="text-subtitle text-muted">Lihat detail inventori</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('department-inventories.index') }}">Inventories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <!-- Status Warnings -->
                @if($departmentInventory->needsReorder())
                    <div class="status-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>Perlu Reorder!</strong> Stok saat ini {{ $departmentInventory->current_stock }} {{ $departmentInventory->unit }},
                        sudah mencapai titik reorder ({{ $departmentInventory->reorder_point }}).
                    </div>
                @elseif($departmentInventory->isBelowMinimum())
                    <div class="status-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Stok Rendah!</strong> Stok saat ini di bawah minimum ({{ $departmentInventory->min_stock }}).
                    </div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">
                            {{ $departmentInventory->department->name }} - {{ $departmentInventory->material->name }}
                        </h4>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('department-inventories.edit', $departmentInventory) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <a href="{{ route('department-inventories.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Basic Information -->
                        <div class="detail-card">
                            <h6 class="mb-3">
                                <i class="bi bi-info-circle me-2"></i>Informasi Dasar
                            </h6>
                            <div class="detail-row">
                                <span class="detail-label">Department:</span>
                                <span class="detail-value">{{ $departmentInventory->department->name }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Material:</span>
                                <span class="detail-value">{{ $departmentInventory->material->name }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Status:</span>
                                <span class="badge-status badge-{{ $departmentInventory->status }}">
                                    {{ ucfirst($departmentInventory->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Stock Information -->
                        <div class="detail-card">
                            <h6 class="mb-3">
                                <i class="bi bi-box me-2"></i>Informasi Stok
                            </h6>
                            <div class="detail-row">
                                <span class="detail-label">Stok Saat Ini:</span>
                                <span class="detail-value">{{ $departmentInventory->current_stock }} {{ $departmentInventory->unit }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Lokasi Penyimpanan:</span>
                                <span class="detail-value">{{ $departmentInventory->location ?? '—' }}</span>
                            </div>
                        </div>

                        <!-- Stock Management -->
                        <div class="detail-card">
                            <h6 class="mb-3">
                                <i class="bi bi-graph-up me-2"></i>Manajemen Stok
                            </h6>
                            <div class="detail-row">
                                <span class="detail-label">Stok Minimum:</span>
                                <span class="detail-value">{{ $departmentInventory->min_stock ?? '—' }} {{ $departmentInventory->unit }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Stok Maksimal:</span>
                                <span class="detail-value">{{ $departmentInventory->max_stock ?? '—' }} {{ $departmentInventory->unit }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Titik Reorder:</span>
                                <span class="detail-value">{{ $departmentInventory->reorder_point ?? '—' }} {{ $departmentInventory->unit }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Jumlah Reorder:</span>
                                <span class="detail-value">{{ $departmentInventory->reorder_quantity ?? '—' }} {{ $departmentInventory->unit }}</span>
                            </div>
                        </div>

                        <!-- Tracking -->
                        <div class="detail-card">
                            <h6 class="mb-3">
                                <i class="bi bi-clock-history me-2"></i>Tracking
                            </h6>
                            <div class="detail-row">
                                <span class="detail-label">Terakhir Dihitung:</span>
                                <span class="detail-value">
                                    @if($departmentInventory->last_counted_at)
                                        {{ $departmentInventory->last_counted_at->format('d M Y, H:i') }}
                                        <small class="text-muted">({{ $departmentInventory->last_counted_at->diffForHumans() }})</small>
                                    @else
                                        —
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Created:</span>
                                <span class="detail-value">{{ $departmentInventory->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Last Updated:</span>
                                <span class="detail-value">{{ $departmentInventory->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>

                        <!-- Action -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('department-inventories.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                            <a href="{{ route('department-inventories.edit', $departmentInventory) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
