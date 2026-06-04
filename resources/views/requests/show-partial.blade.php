 <div class="modal-header">
     <h4 class="modal-title" id="myModalLabel33">Request by Job</h4>
     <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
         <i data-feather="x"></i>
     </button>
 </div>
 <div class="modal-body">
<div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Request #</th>
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
                                        No requests found
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
 </div>
 <div class="modal-footer">
     <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
         <i class="bx bx-x d-block d-sm-none"></i>
         <span class="d-none d-sm-block">Close</span>
     </button>
     {{-- <button type="button" class="btn btn-primary ms-1" data-bs-dismiss="modal">
         <i class="bx bx-check d-block d-sm-none"></i>
         <span class="d-none d-sm-block">login</span>
     </button> --}}
 </div>
