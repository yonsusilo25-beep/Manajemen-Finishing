@extends('layouts.app')

@section('title', 'Finished Product Inspection')

@section('content')
    <div class="page-heading mb-4">
        <h3>Finished Product Inspection</h3>
        <p class="text-muted mb-0">Monitoring inspection requests, approval status, and material readiness.</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Requests</h6>
                            <h3 class="mb-0">{{ $stats['total_requests'] }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-file-earmark-check" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Pending Inspection</h6>
                            <h3 class="mb-0">{{ $stats['pending_requests'] }}</h3>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-clock-history" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">
                                @if(auth()->user()->isWarehouse() || auth()->user()->isAdmin())
                                    Low Stock
                                @else
                                    Approved
                                @endif
                            </h6>
                            <h3 class="mb-0">
                                @if(auth()->user()->isWarehouse() || auth()->user()->isAdmin())
                                    {{ $stats['low_stock_materials'] ?? 0 }}
                                @else
                                    {{ $stats['approved_requests'] }}
                                @endif
                            </h3>
                        </div>
                        <div class="text-success">
                            <i class="bi {{ auth()->user()->isWarehouse() || auth()->user()->isAdmin() ? 'bi-exclamation-triangle' : 'bi-check-circle' }}" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">
                                @if(auth()->user()->isWarehouse() || auth()->user()->isAdmin())
                                    Total Materials
                                @else
                                    Completed
                                @endif
                            </h6>
                            <h3 class="mb-0">
                                @if(auth()->user()->isWarehouse() || auth()->user()->isAdmin())
                                    {{ $stats['total_materials'] ?? 0 }}
                                @else
                                    {{ $stats['completed_requests'] }}
                                @endif
                            </h3>
                        </div>
                        <div class="text-info">
                            <i class="bi {{ auth()->user()->isWarehouse() || auth()->user()->isAdmin() ? 'bi-box-seam' : 'bi-check2-all' }}" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Recent Inspection Requests</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Request #</th>
                                    <th>Department</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Urgency</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_requests as $request)
                                    <tr>
                                        <td>{{ $request->request_number }}</td>
                                        <td>{{ $request->department->name }}</td>
                                        <td>{{ $request->request_date->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->getStatusBadgeClass() }}">
                                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $request->getUrgencyBadgeClass() }}">
                                                {{ ucfirst($request->urgency) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('requests.show', $request) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No inspection requests found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Low Stock Alert</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($low_stock as $material)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $material->name }}</h6>
                                        <small class="text-muted">{{ $material->code }}</small>
                                    </div>
                                    <span class="badge bg-danger">{{ $material->current_stock }} {{ $material->unit }}</span>
                                </div>
                                <div class="progress mt-2" style="height: 5px;">
                                    <div class="progress-bar bg-danger" style="width: {{ min($material->stockPercentage(), 100) }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted">All materials have sufficient stock</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
