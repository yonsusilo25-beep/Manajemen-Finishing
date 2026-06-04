@extends('layouts.app')

@section('title', 'Create Purchase Order')

@section('content')
    <div class="mb-4">
        <h2>Create Purchase Order</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('requests.index') }}">Purchase Order</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Validation Error!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Error!</strong>
            <p class="mb-0">{{ session('error') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <strong>Success!</strong>
            <p class="mb-0">{{ session('success') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('requests.store') }}" method="POST" id="requestForm">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <!-- Purchase Order Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Purchase Order Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Department <span class="text-danger">*</span></label>
                                <select name="department_id" id="departmentSelect"
                                    class="form-select @error('department_id') is-invalid @enderror" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}"
                                            {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Request Date <span class="text-danger">*</span></label>
                                <input type="date" name="request_date"
                                    class="form-control @error('request_date') is-invalid @enderror"
                                    value="{{ old('request_date', date('Y-m-d')) }}" required>
                                @error('request_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Job/Batch Number <span class="text-danger">*</span></label>
                            <select name="job_id" id="jobSelect" class="form-select @error('job_id') is-invalid @enderror"
                                required>
                                <option value="">Select Job/Batch</option>
                                @foreach ($jobs as $job)
                                    <option value="{{ $job->id }}" data-urgency="{{ $job->auto_urgency }}"
                                        data-urgency-class="{{ $job->urgency_badge_class }}"
                                        {{ old('job_id') == $job->id ? 'selected' : '' }}>
                                        {{ $job->job_number }} - {{ $job->product_name }} ({{ $job->boms->count() }}
                                        items)
                                    </option>
                                @endforeach
                            </select>
                            @error('job_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="jobInfo" class="mt-2" style="display: none;">
                                <div class="alert alert-info">
                                    <strong>Job Details:</strong>
                                    <div id="jobDetails"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Request Type <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="request_type" id="typeNormal"
                                    value="normal" checked>
                                <label class="form-check-label" for="typeNormal">
                                    Normal Request (From BOM)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="request_type" id="typeAdditional"
                                    value="additional">
                                <label class="form-check-label" for="typeAdditional">
                                    Additional Request (Outside BOM)
                                </label>
                            </div>
                        </div>

                        <div id="additionalReasonDiv" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Reason for Additional Request <span
                                        class="text-danger">*</span></label>
                                <textarea name="additional_reason" class="form-control" rows="3"
                                    placeholder="Explain why additional materials are needed..."></textarea>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes (optional)">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- BOM Materials -->
                <div class="card" id="bomCard" style="display: none;">
                    <div class="card-header">
                        <h5 class="mb-0">Materials from BOM</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="bomTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="bom-items-tab" data-bs-toggle="tab"
                                    data-bs-target="#bom-items" type="button" role="tab">
                                    BOM Items
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="job-requests-tab" data-bs-toggle="tab"
                                    data-bs-target="#job-requests" type="button" role="tab">
                                    Job Requests
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="bomTabsContent">
                            <div class="tab-pane fade show active" id="bom-items" role="tabpanel">
                                <div id="bomItemsContainer">
                                    <!-- BOM items will be loaded here -->
                                </div>
                            </div>

                            <div class="tab-pane fade" id="job-requests" role="tabpanel">
                                <div id="jobRequestsContainer">
                                    @if (isset($job) && $job->requests)
                                        @foreach ($job->requests as $request)
                                            <div class="mb-3 p-3 border rounded">
                                                <!-- Tampilkan data request disini -->
                                                <p><strong>Request ID:</strong> {{ $request->id }}</p>
                                                <!-- Tambahkan field lain sesuai kebutuhan -->
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">No job requests available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Materials -->
                <div class="card mt-3" id="additionalCard" style="display: none;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Additional Materials</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addAdditionalBtn">
                            <i class="bi bi-plus-circle"></i> Add Material
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="additionalItemsContainer">
                            <!-- Additional items will be added here -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card position-sticky" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0">Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Selected Job:</small>
                            <div id="selectedJob" class="fw-bold">-</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Department:</small>
                            <div id="selectedDept" class="fw-bold">-</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Urgency:</small>
                            <div id="urgencyBadge">-</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Total Items:</small>
                            <div id="totalItems" class="fw-bold">0</div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary w-100 mb-2" id="submitBtn">
                            <i class="bi bi-save"></i> Save as Draft
                        </button>
                        <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const materials = @json($materials);
        const jobs = @json($jobs->load('boms.material', 'boms.department'));
        let additionalItemCount = 0;

        // Handle job selection
        document.getElementById('jobSelect').addEventListener('change', function() {
            const jobId = this.value;
            const departmentId = document.getElementById('departmentSelect').value;

            if (!departmentId) {
                alert('Please select department first');
                this.value = '';
                return;
            }

            if (jobId) {
                loadJobBom(jobId, departmentId);
                getRequestByJob(jobId);
            } else {
                document.getElementById('bomCard').style.display = 'none';
                document.getElementById('bomItemsContainer').innerHTML = '';
            }
        });

        // Handle department selection
        document.getElementById('departmentSelect').addEventListener('change', function() {
            const jobId = document.getElementById('jobSelect').value;
            if (jobId) {
                loadJobBom(jobId, this.value);
            }
            updateSummary();
        });

        // Handle request type change
        document.querySelectorAll('input[name="request_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const isAdditional = this.value === 'additional';
                document.getElementById('additionalReasonDiv').style.display = isAdditional ? 'block' :
                    'none';
                document.getElementById('additionalCard').style.display = isAdditional ? 'block' : 'none';

                if (isAdditional) {
                    document.querySelector('textarea[name="additional_reason"]').required = true;
                } else {
                    document.querySelector('textarea[name="additional_reason"]').required = false;
                }
            });
        });

        // Load job BOM
        function loadJobBom(jobId, departmentId) {
            const job = jobs.find(j => j.id == jobId);
            if (!job) return;
            console.log(job);
            // Update job info
            document.getElementById('selectedJob').textContent = `${job.job_number} - ${job.product_name}`;
            const urgencyBadge = document.getElementById('urgencyBadge');
            urgencyBadge.innerHTML = `<span class="badge bg-${job.urgency_badge_class}">${job.auto_urgency}</span>`;

            // Filter BOM by department
            const departmentBoms = job.boms.filter(bom => bom.department_id == departmentId);
            const otherBoms = job.boms.filter(bom => bom.department_id != departmentId);

            let html = '';

            if (departmentBoms.length > 0) {
                html += '<h6 class="text-success mb-3"><i class="bi bi-check-circle"></i> Your Department Materials</h6>';
                departmentBoms.forEach((bom, index) => {
                    html += generateBomItemHtml(bom, index, true);
                });
            }

            if (otherBoms.length > 0) {
                html +=
                    '<h6 class="text-muted mt-4 mb-3"><i class="bi bi-info-circle"></i> Other Departments (Reference Only)</h6>';
                otherBoms.forEach((bom, index) => {
                    html += generateBomItemHtml(bom, index, false);
                });
            }

            document.getElementById('bomItemsContainer').innerHTML = html;
            document.getElementById('bomCard').style.display = 'block';
            updateSummary();
        }

        function generateBomItemHtml(bom, index, isEditable) {
            const disabled = !isEditable ? 'disabled' : '';
            const bgClass = !isEditable ? 'bg-light' : '';

            // Check stock status
            const currentStock = bom.material.current_stock;
            const requestQty = bom.quantity_required;
            const stockStatus = currentStock >= requestQty ? 'bg-success' : 'bg-warning';
            const stockStatusText = currentStock >= requestQty ? '✓ Cukup' : '⚠ Kurang';
            const stockColor = currentStock >= requestQty ? 'success' : 'warning';

            return `
        <div class="card mb-2 ${bgClass}">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <strong>${bom.material.name}</strong><br>
                        <small class="text-muted">${bom.material.code}</small><br>
                        <span class="badge bg-info">${bom.department.name}</span>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small"><strong>Available Stock</strong></label>
                        <div class="p-2 border rounded bg-light">
                            <strong class="text-${stockColor}">${currentStock} ${bom.unit}</strong><br>
                            <small class="badge bg-${stockColor}">${stockStatusText}</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Required</label>
                        <input type="text" class="form-control form-control-sm" value="${bom.quantity_required} ${bom.unit}" disabled>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Request Qty ${isEditable ? '<span class="text-danger">*</span>' : ''}</label>
                        <input type="number"
                            name="items[${index}][quantity]"
                            class="form-control form-control-sm bom-quantity"
                            value="${bom.quantity_required}"
                            step="0.01"
                            min="0.01"
                            ${disabled}
                            ${isEditable ? 'required' : ''}>
                        <input type="hidden" name="items[${index}][job_bom_id]" value="${bom.id}">
                        <input type="hidden" name="items[${index}][material_id]" value="${bom.material_id}">
                        <input type="hidden" name="items[${index}][is_additional]" value="false">
                    </div>
                    <div class="col-md-1 text-center">
                        ${isEditable ? '<i class="bi bi-check-circle text-success"></i>' : '<i class="bi bi-lock text-muted"></i>'}
                    </div>
                </div>
                ${bom.notes ? `<div class="mt-2"><small class="text-muted"><i class="bi bi-info-circle"></i> ${bom.notes}</small></div>` : ''}
            </div>
        </div>
    `;
        }

        // Add additional material
        document.getElementById('addAdditionalBtn').addEventListener('click', function() {
            additionalItemCount++;
            const html = `
        <div class="card mb-3" id="additional-${additionalItemCount}">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <h6>Additional Item #${additionalItemCount}</h6>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeAdditional(${additionalItemCount})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="row mb-2">
                    <div class="col-md-8">
                        <label class="form-label">Material <span class="text-danger">*</span></label>
                        <select name="items[add_${additionalItemCount}][material_id]" class="form-select" required onchange="updateAdditionalUnit(${additionalItemCount})">
                            <option value="">Select Material</option>
                            ${materials.map(m => `<option value="${m.id}" data-unit="${m.unit}">${m.name} (${m.code})</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="items[add_${additionalItemCount}][quantity]" class="form-control" step="0.01" min="0.01" required>
                        <small class="text-muted" id="unit-add-${additionalItemCount}"></small>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label">Reason <span class="text-danger">*</span></label>
                    <textarea name="items[add_${additionalItemCount}][additional_reason]" class="form-control" rows="2" placeholder="Why is this additional material needed?" required></textarea>
                </div>
                <input type="hidden" name="items[add_${additionalItemCount}][is_additional]" value="true">
                <input type="hidden" name="items[add_${additionalItemCount}][job_bom_id]" value="">
            </div>
        </div>
    `;
            document.getElementById('additionalItemsContainer').insertAdjacentHTML('beforeend', html);
            updateSummary();
        });

        window.removeAdditional = function(id) {
            if (confirm('Remove this additional item?')) {
                document.getElementById(`additional-${id}`).remove();
                updateSummary();
            }
        };

        window.updateAdditionalUnit = function(id) {
            const select = document.querySelector(`select[name="items[add_${id}][material_id]"]`);
            const unit = select.options[select.selectedIndex].dataset.unit;
            document.getElementById(`unit-add-${id}`).textContent = unit ? `Unit: ${unit}` : '';
        };

        function getRequestByJob(jobId) {
            const jobRequestsContainer = document.getElementById('jobRequestsContainer');

            if (!jobId) {
                jobRequestsContainer.innerHTML = '';
                return;
            }

            // Show loading
            jobRequestsContainer.innerHTML =
                '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';

            // Fetch job requests
            fetch(`/jobs/${jobId}/requests`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        //console.log(data.requests);
                        displayJobRequests(data.requests);
                    } else {
                        jobRequestsContainer.innerHTML = '<p class="text-danger">Failed to load job requests</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    jobRequestsContainer.innerHTML = '<p class="text-danger">Error loading job requests</p>';
                });
        }

        function displayJobRequests(requests) {
            const container = document.getElementById('jobRequestsContainer');

            if (!requests || requests.length === 0) {
                container.innerHTML = '<p class="text-muted">No job requests available</p>';
                return;
            }

            // Group by department_id
            const grouped = requests.reduce((acc, request) => {
                const deptId = request.department_id || 'undefined';
                if (!acc[deptId]) {
                    acc[deptId] = [];
                }
                acc[deptId].push(request);
                return acc;
            }, {});

            // Build HTML
            let html = '';
            for (const [deptId, deptRequests] of Object.entries(grouped)) {
                const deptName = deptRequests[0].department?.name || 'Unknown Department';

                html += `
            <div class="mb-4">
                <h6 class="border-bottom pb-2 mb-3">${deptName}</h6>
                <div class="row">
        `;

                deptRequests.forEach(request => {
                    html += `
                <div class="col-12 mb-2">
                    <div class="p-3 border rounded">
                        <p class="mb-1"><strong>Request by ${request.department.name}</strong></p>
                        <p class="mb-1">Status: <span class="badge bg-info">${request.status}</span></p>
                        <p class="mb-0 text-muted small">on ${formatter.format(new Date(request.created_at) )}</p>
                    </div>
                </div>
            `;
                });

                html += `
                </div>
            </div>
        `;
            }

            container.innerHTML = html;
        }

        const formatter = new Intl.DateTimeFormat('id-ID', {
            dateStyle: 'long',
            timeStyle: 'short'
        });

        function updateSummary() {
            const deptSelect = document.getElementById('departmentSelect');
            const selectedDept = deptSelect.options[deptSelect.selectedIndex].text;
            document.getElementById('selectedDept').textContent = selectedDept || '-';

            const bomItems = document.querySelectorAll('.bom-quantity:not([disabled])').length;
            const additionalItems = document.querySelectorAll('#additionalItemsContainer .card').length;
            document.getElementById('totalItems').textContent = bomItems + additionalItems;
        }

        // Form validation
        document.getElementById('requestForm').addEventListener('submit', function(e) {
            const totalItems = parseInt(document.getElementById('totalItems').textContent);
            if (totalItems === 0) {
                e.preventDefault();
                alert('Please select at least one material to request');
                return false;
            }
        });

        // Update summary on load
        updateSummary();
    </script>
@endpush
