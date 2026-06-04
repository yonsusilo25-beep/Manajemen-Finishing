@extends('layouts.app')

@section('title', 'Department Inventories')

@push('styles')
    <style>
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

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ auth()->user()->department->name ?? 'Department' }} Inventories</h3>
                    <p class="text-subtitle text-muted">Kelola inventori per department</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Inventories</li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Daftar Inventori</h4>
                            <small class="text-muted d-block mt-1">Data inventori otomatis tercatat saat request
                                di-approve</small>
                        </div>

                        <div class="card-body">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-2"></i>
                                    {{ $message }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (!auth()->user()->department_id)
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <form action="{{ route('department-inventories.index') }}" method="GET"
                                            class="d-flex gap-2">
                                            <select name="department_id" class="form-select form-select-sm">
                                                <option value="">Semua Department</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ $selectedDepartment == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                            <!-- Filter -->

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Department</th>
                                            <th>Material</th>
                                            <th>Stok Saat Ini</th>
                                            <th>Min/Max</th>
                                            <th>Lokasi</th>
                                            <th>Status</th>
                                            <th>Terakhir Dihitung</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($inventories as $inventory)
                                            <tr>
                                                <td>
                                                    <strong>{{ $inventory->department->name }}</strong>
                                                </td>
                                                <td>
                                                    {{ $inventory->material->name }}
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $inventory->current_stock }}</strong>
                                                        {{ $inventory->unit }}
                                                    </div>
                                                    @if ($inventory->isBelowMinimum())
                                                        <small class="badge badge-warning">
                                                            <i class="bi bi-exclamation-triangle me-1"></i>Stok Rendah
                                                        </small>
                                                    @endif
                                                    @if ($inventory->needsReorder())
                                                        <small class="badge bg-danger">
                                                            <i class="bi bi-exclamation-circle me-1"></i>Perlu Reorder
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($inventory->min_stock || $inventory->max_stock)
                                                        <small class="text-muted">
                                                            {{ $inventory->min_stock ?? '—' }} /
                                                            {{ $inventory->max_stock ?? '—' }}
                                                        </small>
                                                    @else
                                                        <small class="text-muted">—</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $inventory->location ?? '—' }}
                                                </td>
                                                <td>
                                                    <span class="badge-status badge-{{ $inventory->status }}">
                                                        {{ ucfirst($inventory->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        @if ($inventory->last_counted_at)
                                                            {{ $inventory->last_counted_at->diffForHumans() }}
                                                        @else
                                                            —
                                                        @endif
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('department-inventories.show', $inventory) }}"
                                                            class="btn btn-outline-info" title="Detail">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('department-inventories.edit', $inventory) }}"
                                                            class="btn btn-outline-warning" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">
                                                    <i class="bi bi-inbox me-2"></i>Tidak ada data inventori
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-end mt-3">
                                {{ $inventories->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
