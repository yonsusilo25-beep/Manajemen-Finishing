@extends('layouts.app')

@section('title', 'Purchase Order')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Purchase Order</h2>
        <a href="{{ route('requests.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Purchase Order
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('requests.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by request #, job #"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted
                            </option>
                            <option value="checking_stock" {{ request('status') == 'checking_stock' ? 'selected' : '' }}>
                                Checking Stock</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                    </div>
                    @if (auth()->user()->isWarehouse() || auth()->user()->isAdmin())
                        <div class="col-md-3">
                            <select name="department" class="form-select">
                                <option value="">All Departments</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}"
                                        {{ request('department') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Purchase Order Table -->
    @if (!auth()->user()->isWarehouse())
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>PO #</th>
                                <th>Department</th>
                                <th>Requestor</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Urgency</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                <tr>
                                    <td>
                                        <a href="{{ route('requests.show', $request) }}"
                                            class="text-decoration-none fw-bold">
                                            {{ $request->request_number }}
                                        </a>
                                        @if ($request->job_number)
                                            <br><small class="text-muted">Job: {{ $request->job_number }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $request->department->name }}</td>
                                    <td>{{ $request->user->name }}</td>
                                    <td>{{ $request->request_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $request->items->count() }} items</span>
                                    </td>
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
                                        <a href="{{ route('requests.show', $request) }}"
                                            class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        No purchase orders found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($requests->hasPages())
                <div class="card-footer">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    @else
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
                                <th width="25%">Job Number</th>
                                <th width="15%">job Name</th>
                                <th width="15%">Quantity</th>
                                <th width="12%" class="text-end">Requested Materials</th>
                                {{-- <th width="9%">Status</th> --}}
                                <th width="5%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobsData as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $item->job_number ?? 'N/A' }}</strong><br>
                                        {{-- <small class="text-muted">{{ $item->material->name ?? 'N/A' }}</small> --}}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $item->job_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format($item->quantity, 2) }}</strong>
                                    </td>
                                    <td class="text-end">
                                        {{ $item->requests()->count() }} permintaan

                                    </td>
                                    <td>
                                        <a href="#" data-id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#showJobRequestModal" class="btn btn-sm btn-info show-btn"><i class="bi bi-eye"></i></a>
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
                        {{-- @if ($request->items->count() > 0)
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
                        @endif --}}
                    </table>
                </div>
            </div>
        </div>

    @endif

    <div class="modal fade text-left" id="showJobRequestModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content" id="modalContent">
               
            </div>
        </div>
    </div>

@endsection

@push('scripts')

<script>

    const modalContent = document.getElementById('modalContent');

// 1. Tambahkan titik (.) jika show-btn adalah class, atau gunakan tag name
document.querySelectorAll('.show-btn').forEach(button => {
    button.addEventListener('click', function() {
        // 2. Ambil ID dari dataset atribut (misal: data-id="123")
        const id = this.getAttribute('data-id');

        modalContent.innerHTML = '';

        // 3. Langsung fetch tanpa perlu foreach tambahan di dalam
        fetch(`/jobs/${id}/all`)
            .then(response => response.text()) // Ambil sebagai text/html
            .then(data => {
                // 4. Gunakan innerHTML untuk memasukkan konten
                modalContent.innerHTML = data;
            })
            .catch(err => console.error('Error:', err));
    });
});


</script>

@endpush
