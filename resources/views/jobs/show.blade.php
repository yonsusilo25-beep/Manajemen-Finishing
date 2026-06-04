@extends('layouts.app')

@section('title', 'View Job')

@push('styles')
<style>
    .detail-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    .detail-value {
        font-size: 1.1rem;
        color: #212529;
        margin-bottom: 1.5rem;
    }
    .badge {
        padding: 0.5em 0.85em;
        font-size: 0.95rem;
        font-weight: 500;
    }
    .card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    }
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }
    .info-card .card-body {
        padding: 1.5rem;
    }
    .info-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        font-size: 0.85rem;
        opacity: 0.9;
        margin-bottom: 0.25rem;
    }
    .info-value {
        font-size: 1.25rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Job Details</h3>
                <p class="text-subtitle text-muted">View complete job information</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('jobs.index') }}">Jobs</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $job->job_number }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">{{ $job->job_name }}</h4>
                        <div>
                            <a href="{{ route('jobs.edit', $job) }}" class="btn btn-warning btn-sm me-2">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <a href="{{ route('jobs.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-label">Job Number</div>
                                <div class="detail-value">
                                    <span class="badge bg-dark">{{ $job->job_number }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Status</div>
                                <div class="detail-value">
                                    <span class="badge {{ $job->status_badge }}">
                                        {{ $job->status_label }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="detail-label">Job Name</div>
                                <div class="detail-value">{{ $job->job_name }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-label">Product Name</div>
                                <div class="detail-value">{{ $job->product_name }}</div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Quantity</div>
                                <div class="detail-value">
                                    <i class="bi bi-box-seam me-2 text-primary"></i>
                                    {{ number_format($job->quantity) }} units
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-label">Start Date</div>
                                <div class="detail-value">
                                    <i class="bi bi-calendar-check me-2 text-success"></i>
                                    {{ $job->start_date->format('d M Y') }}
                                    <small class="text-muted">({{ $job->start_date->diffForHumans() }})</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Due Date</div>
                                <div class="detail-value">
                                    <i class="bi bi-calendar-x me-2 text-danger"></i>
                                    {{ $job->due_date->format('d M Y') }}
                                    <small class="text-muted">({{ $job->due_date->diffForHumans() }})</small>
                                </div>
                            </div>
                        </div>

                        @if($job->notes)
                            <div class="row">
                                <div class="col-12">
                                    <div class="detail-label">Notes</div>
                                    <div class="detail-value">
                                        <div class="alert alert-light border">
                                            {{ $job->notes }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row mt-3 pt-3 border-top">
                            <div class="col-md-6">
                                <div class="detail-label">Created By</div>
                                <div class="detail-value">
                                    <i class="bi bi-person-circle me-2 text-info"></i>
                                    {{ $job->creator->name ?? 'Unknown' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Created At</div>
                                <div class="detail-value">
                                    <i class="bi bi-clock me-2 text-secondary"></i>
                                    {{ $job->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="text-white mb-3">
                            <i class="bi bi-graph-up me-2"></i>Quick Stats
                        </h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-item">
                                    <div class="info-label">Duration</div>
                                    <div class="info-value">
                                        {{ $job->start_date->diffInDays($job->due_date) }} days
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-item">
                                    <div class="info-label">Days Remaining</div>
                                    <div class="info-value">
                                        {{ now()->diffInDays($job->due_date, false) > 0 ? now()->diffInDays($job->due_date) : 0 }} days
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-item">
                                    <div class="info-label">BOM Items</div>
                                    <div class="info-value">
                                        {{ $job->boms->count() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bill of Materials -->
                @if($job->boms->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Bill of Materials (BOM)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="25%">Material</th>
                                        <th width="15%">Category</th>
                                        <th width="20%">Department</th>
                                        <th width="15%" class="text-end">Qty Required</th>
                                        <th width="10%">Unit</th>
                                        <th width="10%">Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($job->boms as $index => $bom)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $bom->material->code ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $bom->material->name ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                @if($bom->material->category)
                                                    <span class="badge bg-light text-dark">
                                                        {{ $bom->material->category->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="bi bi-building me-1 text-primary"></i>
                                                {{ $bom->department->name ?? 'N/A' }}
                                            </td>
                                            <td class="text-end">
                                                <strong>{{ number_format($bom->quantity_required, 2) }}</strong>
                                            </td>
                                            <td>{{ $bom->unit }}</td>
                                            <td>
                                                @if($bom->notes)
                                                    <i class="bi bi-chat-left-text text-info"
                                                       data-bs-toggle="tooltip"
                                                       title="{{ $bom->notes }}"></i>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4" class="text-end">Total Items:</th>
                                        <th class="text-end">{{ $job->boms->count() }}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    // Optional: Add any interactive features here
    console.log('Job details loaded: {{ $job->job_number }}');

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush
