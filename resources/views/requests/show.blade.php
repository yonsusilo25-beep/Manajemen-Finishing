@extends('layouts.app')

@section('title', 'Purchase Order Details')

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

        .status-badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }

        .urgency-badge {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -24px;
            top: 8px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #6c757d;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #e9ecef;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: -19px;
            top: 20px;
            width: 2px;
            height: calc(100% - 8px);
            background-color: #e9ecef;
        }

        .timeline-item:last-child::after {
            display: none;
        }

        .timeline-item.active::before {
            background-color: #0d6efd;
            box-shadow: 0 0 0 2px #cfe2ff;
        }

        .timeline-item.success::before {
            background-color: #198754;
            box-shadow: 0 0 0 2px #d1e7dd;
        }

        .timeline-item.danger::before {
            background-color: #dc3545;
            box-shadow: 0 0 0 2px #f8d7da;
        }

        .items-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-box h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .info-box p {
            opacity: 0.9;
            margin-bottom: 0;
        }

        .stat-card {
            text-align: center;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            border: 1px solid #e9ecef;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 0.25rem;
        }

        .stat-card .stat-label {
            font-size: 0.875rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .action-buttons .btn {
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Purchase Order Details</h3>
                    <p class="text-subtitle text-muted">View complete purchase order information</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('requests.index') }}">Purchase Order</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $request->request_number }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <!-- Header Info Box -->
            <div class="info-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2>{{ $request->request_number }}</h2>
                        <p class="mb-2">
                            <i class="bi bi-building me-2"></i>{{ $request->department->name ?? 'N/A' }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-briefcase me-2"></i>{{ $request->job->job_name ?? 'N/A' }}
                        </p>
                        <p>
                            <i class="bi bi-person me-2"></i>Requested by: {{ $request->user->name ?? 'N/A' }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-calendar me-2"></i>{{ $request->request_date->format('d M Y') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="badge status-badge bg-{{ $request->getStatusBadgeClass() }}">
                            {{ ucwords(str_replace('_', ' ', $request->status)) }}
                        </span>
                        <br>
                        <span class="badge urgency-badge bg-{{ $request->urgency_badge_class }} mt-2">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            {{ ucfirst($request->urgency) }} Priority
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="action-buttons d-flex flex-wrap">
                        <a href="{{ route('requests.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>

                        @php
                            $allowedSubmitStatus = in_array($request->status, ['draft']);
                            $allowedSubmitRole = auth()->user()->isSupervisor();
                        @endphp
                        @if ($request->status === 'draft' && $allowedSubmitRole)
                            <a href="{{ route('requests.edit', $request) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <form action="{{ route('requests.submit', $request) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary"
                                    onclick="return confirm('Submit this request?')">
                                    <i class="bi bi-send me-1"></i> Submit Request
                                </button>
                            </form>
                        @endif
                        @php
                            $isAllowedStatus = in_array($request->status, ['submitted', 'checking_stock']);
                            $isAuthorizedRole = auth()->user()->isAdmin() || auth()->user()->isWarehouse();
                        @endphp
                        @if ($isAllowedStatus && $isAuthorizedRole)
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#approveModal">
                                <i class="bi bi-check-circle me-1"></i> Approve
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle me-1"></i> Reject
                            </button>
                        @endif

                        @if ($request->status === 'approved')
                            <form action=""></form>
                        @endif

                        @if ($request->status === 'ready_for_pickup')
                            <form action="{{ route('requests.complete', $request) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Mark as completed?')">
                                    <i class="bi bi-check-circle me-1"></i> Mark as Completed
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('requests.print', $request) }}" class="btn btn-outline-primary">
                            <i class="bi bi-printer me-1"></i> Print PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Purchase Order Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Purchase Order Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-label">Request Number</div>
                                    <div class="detail-value">
                                        <strong>{{ $request->request_number }}</strong>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="detail-label">Request Date</div>
                                    <div class="detail-value">
                                        <i class="bi bi-calendar3 me-2 text-primary"></i>
                                        {{ $request->request_date->format('d M Y') }}
                                        <small class="text-muted">({{ $request->request_date->diffForHumans() }})</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="detail-label">Department</div>
                                    <div class="detail-value">
                                        <i class="bi bi-building me-2 text-info"></i>
                                        {{ $request->department->name ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="detail-label">Requested By</div>
                                    <div class="detail-value">
                                        <i class="bi bi-person me-2 text-success"></i>
                                        {{ $request->user->name ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="detail-label">Related Job</div>
                                    <div class="detail-value">
                                        @if ($request->job)
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-briefcase me-2 text-warning"></i>
                                                <div>
                                                    <strong>{{ $request->job->job_number }}</strong> -
                                                    {{ $request->job->job_name }}
                                                    <br>
                                                    <small class="text-muted">
                                                        Product: {{ $request->job->product_name }} |
                                                        Quantity: {{ number_format($request->job->quantity) }}
                                                    </small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">No job associated</span>
                                        @endif
                                    </div>
                                </div>

                                @if ($request->notes)
                                    <div class="col-12">
                                        <div class="detail-label">Notes</div>
                                        <div class="detail-value">
                                            <div class="alert alert-light border mb-0">
                                                <i class="bi bi-sticky me-2"></i>
                                                {{ $request->notes }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Approval/Rejection Information -->
                            @if ($request->status === 'approved' || $request->status === 'partial_approved')
                                <div class="row mt-3 pt-3 border-top">
                                    <div class="col-md-6">
                                        <div class="detail-label">Approved By</div>
                                        <div class="detail-value">
                                            <i class="bi bi-person-check me-2 text-success"></i>
                                            {{ $request->approvedBy->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-label">Approved At</div>
                                        <div class="detail-value">
                                            <i class="bi bi-clock me-2 text-success"></i>
                                            {{ $request->approved_at ? $request->approved_at->format('d M Y, H:i') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($request->status === 'rejected')
                                <div class="row mt-3 pt-3 border-top">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            <h6 class="alert-heading"><i class="bi bi-x-circle me-2"></i>Rejection
                                                Information</h6>
                                            <p class="mb-2"><strong>Rejected By:</strong>
                                                {{ $request->approvedBy->name ?? 'N/A' }}</p>
                                            <p class="mb-2"><strong>Rejected At:</strong>
                                                {{ $request->approved_at ? $request->approved_at->format('d M Y, H:i') : 'N/A' }}
                                            </p>
                                            <hr>
                                            <p class="mb-0">
                                                <strong>Reason:</strong><br>{{ $request->rejection_reason ?? 'No reason provided' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($request->status === 'completed')
                                <div class="row mt-3 pt-3 border-top">
                                    <div class="col-md-6">
                                        <div class="detail-label">Completed By</div>
                                        <div class="detail-value">
                                            <i class="bi bi-person-check me-2 text-success"></i>
                                            {{ $request->completedBy->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-label">Completed At</div>
                                        <div class="detail-value">
                                            <i class="bi bi-clock me-2 text-success"></i>
                                            {{ $request->completed_at ? $request->completed_at->format('d M Y, H:i') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Request Items -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Request Items</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover items-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">Material</th>
                                            <th width="15%">Category</th>
                                            <th width="12%" class="text-end">Requested</th>
                                            <th width="12%" class="text-end">Stock</th>
                                            <th width="12%" class="text-end">Approved</th>
                                            <th width="12%" class="text-end">Issued</th>
                                            <th width="10%">Unit</th>
                                            <th width="9%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($request->items as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $item->material->code ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted">{{ $item->material->name ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        {{ $item->material->category->name ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <strong>{{ number_format($item->quantity_requested, 2) }}</strong>
                                                </td>
                                                <td class="text-end">
                                                    @if ($item->material->current_stock <= $item->material->min_stock)
                                                        {{-- KRITIS: Stok di bawah atau sama dengan batas minimum --}}
                                                        <strong
                                                            class="text-danger">{{ $item->material->current_stock }}</strong><br>
                                                        <span class="text-muted">Need action! (Low stock)</span>
                                                    @elseif($item->material->current_stock < $item->material->max_stock)
                                                        {{-- AMAN: Stok di atas minimum tapi belum penuh --}}
                                                        <strong
                                                            class="text-warning">{{ $item->material->current_stock }}</strong><br>
                                                        <span class="text-muted">Stock is okay</span>
                                                    @else
                                                        {{-- PENUH/OVER: Stok mencapai atau melebihi kapasitas gudang --}}
                                                        <strong
                                                            class="text-success">{{ $item->material->current_stock }}</strong><br>
                                                        <span class="text-muted">Stock is full/safe</span>
                                                    @endif

                                                </td>
                                                <td class="text-end">
                                                    @if ($item->quantity_approved)
                                                        <strong
                                                            class="text-success">{{ number_format($item->quantity_approved, 2) }}</strong><br>
                                                            <small class="text-muted">{{ round($item->material->current_stock/$item->material->capacity_per_pack) }} {{ $item->material->packing_unit }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    @if ($item->quantity_issued)
                                                        <strong
                                                            class="text-info">{{ number_format($item->quantity_issued, 2) }}</strong>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->material->unit ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($item->quantity_issued)
                                                        <span class="badge bg-success">Issued</span>
                                                    @elseif($item->quantity_approved)
                                                        <span class="badge bg-warning">Approved</span>
                                                    @else
                                                        <span class="badge bg-secondary">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if ($item->notes)
                                                <tr>
                                                    <td colspan="8" class="bg-light">
                                                        <small class="text-muted">
                                                            <i class="bi bi-chat-left-text me-1"></i>
                                                            <strong>Note:</strong> {{ $item->notes }}
                                                        </small>
                                                    </td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                                    <p class="mt-2">No items in this request</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($request->items->count() > 0)
                                        <tfoot class="table-light">
                                            <tr>
                                                <th colspan="3" class="text-end">Total Items:</th>
                                                <th class="text-end">
                                                    {{ number_format($request->items->sum('quantity_requested'), 2) }}</th>
                                                <th class="text-end">
                                                    {{ number_format($request->items->sum('quantity_approved'), 2) }}</th>
                                                <th class="text-end">
                                                    {{ number_format($request->items->sum('quantity_issued'), 2) }}</th>
                                                <th colspan="2"></th>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Statistics -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="stat-card mb-3">
                                <div class="stat-value text-primary">{{ $request->items->count() }}</div>
                                <div class="stat-label">Total Items</div>
                            </div>
                            <div class="stat-card mb-3">
                                <div class="stat-value text-success">
                                    {{ $request->items->where('quantity_approved', '>', 0)->count() }}
                                </div>
                                <div class="stat-label">Approved Items</div>
                            </div>
                            <div class="stat-card mb-3">
                                <div class="stat-value text-info">
                                    {{ $request->items->where('quantity_issued', '>', 0)->count() }}
                                </div>
                                <div class="stat-label">Issued Items</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value text-warning">
                                    {{ $request->created_at->diffInDays(now()) }}
                                </div>
                                <div class="stat-label">Days Since Created</div>
                            </div>
                        </div>
                    </div>

                    <!-- Status History -->
                    @if ($request->statusHistory && $request->statusHistory->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Status History</h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    @foreach ($request->statusHistory()->orderBy('created_at', 'desc')->get() as $history)
                                        <div
                                            class="timeline-item {{ $loop->first ? 'active' : '' }}
                                        {{ in_array($history->status, ['completed', 'approved']) ? 'success' : '' }}
                                        {{ in_array($history->status, ['rejected', 'cancelled']) ? 'danger' : '' }}">
                                            <div class="mb-1">
                                                <span class="badge bg-{{ $request->getStatusBadgeClass() }}">
                                                    {{ ucwords(str_replace('_', ' ', $history->status)) }}
                                                </span>
                                            </div>
                                            <div class="text-muted small mb-1">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $history->created_at->format('d M Y, H:i') }}
                                            </div>
                                            <div class="small">
                                                <i class="bi bi-person me-1"></i>
                                                {{ $history->user->name ?? 'System' }}
                                            </div>
                                            @if ($history->notes)
                                                <div class="small text-muted mt-1">
                                                    <i class="bi bi-chat-left-text me-1"></i>
                                                    {{ $history->notes }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="{{ route('requests.approve', $request) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel">Approve Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to approve this request?</p>
                        <div class="table-responsive">
                            <table class="table table-hover items-table">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="25%">Material</th>
                                        <th width="15%">Category</th>
                                        <th width="12%" class="text-end">Requested</th>
                                        <th width="12%" class="text-end">Stock</th>
                                        <th width="12%" class="text-end">Approved</th>
                                        <th width="12%" class="text-end">Issued</th>
                                        <th width="10%">Unit</th>
                                        <th width="9%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($request->items as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $item->material->code ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $item->material->name ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $item->material->category->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <strong>{{ number_format($item->quantity_requested, 2) }}</strong>
                                            </td>
                                            <td class="text-end">
                                                @if ($item->material->current_stock <= $item->material->min_stock)
                                                        {{-- KRITIS: Stok di bawah atau sama dengan batas minimum --}}
                                                        <strong
                                                            class="text-danger">{{ $item->material->current_stock }}</strong><br>
                                                        <span class="text-muted">Need action! (Low stock)</span>
                                                    @elseif($item->material->current_stock < $item->material->max_stock)
                                                        {{-- AMAN: Stok di atas minimum tapi belum penuh --}}
                                                        <strong
                                                            class="text-warning">{{ $item->material->current_stock }}</strong><br>
                                                        <span class="text-muted">Stock is okay</span>
                                                    @else
                                                        {{-- PENUH/OVER: Stok mencapai atau melebihi kapasitas gudang --}}
                                                        <strong
                                                            class="text-success">{{ $item->material->current_stock }}</strong><br>
                                                        <span class="text-muted">Stock is full/safe</span>
                                                    @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($item->quantity_approved)
                                                    <strong
                                                        class="text-success">{{ number_format($item->quantity_approved, 2) }}</strong>
                                                @else
                                                    <input type="number"
                                                        name="items[{{ $item->id }}][quantity_approved]"
                                                        id="qtyApprove" class="form-control">
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($item->quantity_issued)
                                                    <strong
                                                        class="text-info">{{ number_format($item->quantity_issued, 2) }}</strong>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->material->unit ?? 'N/A' }}</td>
                                            <td>
                                                @if ($item->quantity_issued)
                                                    <span class="badge bg-success">Issued</span>
                                                @elseif($item->quantity_approved)
                                                    <span class="badge bg-warning">Approved</span>
                                                @else
                                                    <span class="badge bg-secondary">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($item->notes)
                                            <tr>
                                                <td colspan="8" class="bg-light">
                                                    <small class="text-muted">
                                                        <i class="bi bi-chat-left-text me-1"></i>
                                                        <strong>Note:</strong> {{ $item->notes }}
                                                    </small>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                                <p class="mt-2">No items in this request</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if ($request->items->count() > 0)
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="3" class="text-end">Total Items:</th>
                                            <th class="text-end">
                                                {{ number_format($request->items->sum('quantity_requested'), 2) }}</th>
                                            <th class="text-end">
                                                {{ number_format($request->items->sum('quantity_approved'), 2) }}</th>
                                            <th class="text-end">
                                                {{ number_format($request->items->sum('quantity_issued'), 2) }}</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                        <div class="mb-3">
                            <label for="approval_notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="approval_notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Approve
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('requests.reject', $request) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Reject Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to reject this request?</p>
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle me-1"></i> Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto dismiss alerts
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Confirm before submitting forms
        document.querySelectorAll('form[data-confirm]').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (!confirm(form.dataset.confirm)) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
