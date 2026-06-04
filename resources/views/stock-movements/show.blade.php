@extends('layouts.app')

@section('title', 'Stock Movement Details')

@push('styles')
<style>
    .detail-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }
    .detail-value {
        font-size: 1rem;
        color: #212529;
        margin-bottom: 1.25rem;
    }
    .badge {
        padding: 0.5em 0.85em;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid #e9ecef;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
    }
    .info-header {
        background: linear-gradient(135deg,
            {{ $stockMovement->type === 'in' ? '#198754, #20c997' :
               ($stockMovement->type === 'out' ? '#dc3545, #fd7e14' : '#ffc107, #fd7e14') }});
        color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .movement-icon-large {
        font-size: 4rem;
        opacity: 0.9;
        margin-bottom: 1rem;
    }
    .stock-comparison {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1.5rem;
        border: 2px solid #e9ecef;
    }
    .stock-value {
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        padding: 1rem;
    }
    .stock-label {
        font-size: 0.875rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
        margin-bottom: 0.5rem;
    }
    .arrow-icon {
        font-size: 2rem;
        text-align: center;
        padding: 1rem;
        color: #6c757d;
    }
    .change-badge {
        font-size: 1.25rem;
        padding: 0.5rem 1rem;
    }
    .timeline-item {
        padding-left: 2rem;
        position: relative;
        padding-bottom: 1.5rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #0d6efd;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #e9ecef;
    }
    .timeline-item::after {
        content: '';
        position: absolute;
        left: 5px;
        top: 12px;
        width: 2px;
        height: calc(100% - 12px);
        background-color: #e9ecef;
    }
    .timeline-item:last-child::after {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Stock Movement Details</h3>
                <p class="text-subtitle text-muted">View complete movement information</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('stock-movements.index') }}">Stock Movements</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <!-- Info Header -->
        <div class="info-header">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <div class="movement-icon-large">
                        <i class="bi bi-{{ $stockMovement->type_icon }}"></i>
                    </div>
                </div>
                <div class="col-md-7">
                    <h2 class="mb-2">{{ $stockMovement->type_label }}</h2>
                    <p class="mb-2">
                        <i class="bi bi-box-seam me-2"></i>
                        <strong>{{ $stockMovement->material->code ?? 'N/A' }}</strong> -
                        {{ $stockMovement->material->name ?? 'N/A' }}
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-calendar3 me-2"></i>{{ $stockMovement->movement_date->format('d M Y') }}
                        <span class="mx-2">•</span>
                        <i class="bi bi-clock me-2"></i>{{ $stockMovement->created_at->format('H:i:s') }}
                    </p>
                </div>
                <div class="col-md-3 text-center text-md-end mt-3 mt-md-0">
                    <div class="change-badge badge bg-white text-{{ $stockMovement->type_badge_class }}">
                        {{ $stockMovement->formatted_quantity }}
                        {{ $stockMovement->material->unit ?? '' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('stock-movements.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                    @if($stockMovement->request)
                        <a href="{{ route('requests.show', $stockMovement->request) }}" class="btn btn-primary">
                            <i class="bi bi-file-text me-1"></i> View Related Request
                        </a>
                    @endif
                    <a href="{{ route('materials.show', $stockMovement->material) }}" class="btn btn-info">
                        <i class="bi bi-box me-1"></i> View Material
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Stock Comparison -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Stock Level Change</h5>
                    </div>
                    <div class="card-body">
                        <div class="stock-comparison">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="stock-label">Stock Before</div>
                                    <div class="stock-value text-secondary">
                                        {{ number_format($stockMovement->stock_before, 2) }}
                                    </div>
                                    <div class="text-center text-muted">
                                        {{ $stockMovement->material->unit ?? 'units' }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="arrow-icon">
                                        <i class="bi bi-arrow-right"></i>
                                    </div>
                                    <div class="text-center">
                                        <span class="badge change-badge bg-{{ $stockMovement->type_badge_class }}">
                                            {{ $stockMovement->stock_change >= 0 ? '+' : '' }}{{ number_format($stockMovement->stock_change, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stock-label">Stock After</div>
                                    <div class="stock-value text-{{ $stockMovement->stock_change >= 0 ? 'success' : 'danger' }}">
                                        {{ number_format($stockMovement->stock_after, 2) }}
                                    </div>
                                    <div class="text-center text-muted">
                                        {{ $stockMovement->material->unit ?? 'units' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Movement Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Movement Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-label">Movement Type</div>
                                <div class="detail-value">
                                    <span class="badge bg-{{ $stockMovement->type_badge_class }}">
                                        <i class="bi bi-{{ $stockMovement->type_icon }} me-1"></i>
                                        {{ $stockMovement->type_label }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Movement Date</div>
                                <div class="detail-value">
                                    <i class="bi bi-calendar3 me-2 text-primary"></i>
                                    {{ $stockMovement->movement_date->format('d M Y') }}
                                    <small class="text-muted">({{ $stockMovement->movement_date->diffForHumans() }})</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Quantity</div>
                                <div class="detail-value">
                                    <strong class="text-{{ $stockMovement->type_badge_class }}">
                                        {{ $stockMovement->formatted_quantity }}
                                        {{ $stockMovement->material->unit ?? 'units' }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Performed By</div>
                                <div class="detail-value">
                                    <i class="bi bi-person me-2 text-success"></i>
                                    {{ $stockMovement->user->name ?? 'System' }}
                                </div>
                            </div>

                            @if($stockMovement->request)
                                <div class="col-md-12">
                                    <div class="detail-label">Related Request</div>
                                    <div class="detail-value">
                                        <a href="{{ route('requests.show', $stockMovement->request) }}" class="text-decoration-none">
                                            <i class="bi bi-file-text me-2 text-info"></i>
                                            <strong>{{ $stockMovement->request->request_number }}</strong>
                                        </a>
                                        @if($stockMovement->request->job)
                                            <br>
                                            <small class="text-muted">
                                                Job: {{ $stockMovement->request->job->job_number }} -
                                                {{ $stockMovement->request->job->job_name }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($stockMovement->notes)
                                <div class="col-12">
                                    <div class="detail-label">Notes</div>
                                    <div class="detail-value">
                                        <div class="alert alert-light border mb-0">
                                            <i class="bi bi-sticky me-2"></i>
                                            {{ $stockMovement->notes }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row mt-3 pt-3 border-top">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="bi bi-clock-history me-1"></i>
                                    <strong>Recorded at:</strong> {{ $stockMovement->created_at->format('d M Y, H:i:s') }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="bi bi-hash me-1"></i>
                                    <strong>Movement ID:</strong> #{{ $stockMovement->id }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Material Details -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Material Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-label">Material Code</div>
                                <div class="detail-value">
                                    <strong>{{ $stockMovement->material->code ?? 'N/A' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Material Name</div>
                                <div class="detail-value">
                                    {{ $stockMovement->material->name ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Category</div>
                                <div class="detail-value">
                                    @if($stockMovement->material->category)
                                        <span class="badge bg-light text-dark">
                                            {{ $stockMovement->material->category->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">No category</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Unit of Measurement</div>
                                <div class="detail-value">
                                    {{ $stockMovement->material->unit ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Current Stock Level</div>
                                <div class="detail-value">
                                    <strong class="text-primary">
                                        {{ number_format($stockMovement->material->current_stock ?? 0, 2) }}
                                        {{ $stockMovement->material->unit ?? 'units' }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Summary Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Movement Type:</span>
                                <span class="badge bg-{{ $stockMovement->type_badge_class }}">
                                    {{ $stockMovement->type_label }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Quantity:</span>
                                <strong>{{ number_format($stockMovement->quantity, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Unit:</span>
                                <span>{{ $stockMovement->material->unit ?? 'units' }}</span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Stock Before:</span>
                                <strong>{{ number_format($stockMovement->stock_before, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Stock After:</span>
                                <strong>{{ number_format($stockMovement->stock_after, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Change:</span>
                                <strong class="text-{{ $stockMovement->stock_change >= 0 ? 'success' : 'danger' }}">
                                    {{ $stockMovement->stock_change >= 0 ? '+' : '' }}{{ number_format($stockMovement->stock_change, 2) }}
                                </strong>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Date:</span>
                                <span>{{ $stockMovement->movement_date->format('d M Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Time:</span>
                                <span>{{ $stockMovement->created_at->format('H:i:s') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">By:</span>
                                <span>{{ $stockMovement->user->name ?? 'System' }}</span>
                            </div>
                        </div>

                        @if($stockMovement->request)
                            <div>
                                <div class="text-muted mb-2">Related Request:</div>
                                <a href="{{ route('requests.show', $stockMovement->request) }}"
                                   class="btn btn-sm btn-outline-primary w-100">
                                    <i class="bi bi-file-text me-1"></i>
                                    {{ $stockMovement->request->request_number }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Movement Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline-item">
                            <div class="mb-1">
                                <strong>Movement Created</strong>
                            </div>
                            <div class="text-muted small">
                                {{ $stockMovement->created_at->format('d M Y, H:i:s') }}
                            </div>
                            <div class="small">
                                By: {{ $stockMovement->user->name ?? 'System' }}
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="mb-1">
                                <strong>Stock Updated</strong>
                            </div>
                            <div class="text-muted small">
                                {{ $stockMovement->stock_before }} → {{ $stockMovement->stock_after }}
                            </div>
                        </div>

                        @if($stockMovement->request)
                            <div class="timeline-item">
                                <div class="mb-1">
                                    <strong>Request Linked</strong>
                                </div>
                                <div class="text-muted small">
                                    {{ $stockMovement->request->request_number }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    console.log('Stock movement details loaded: #{{ $stockMovement->id }}');
</script>
@endpush
