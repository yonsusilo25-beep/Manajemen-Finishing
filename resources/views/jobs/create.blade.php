@extends('layouts.app')

@section('title', 'Create Job')

@push('styles')
<style>
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .required::after {
        content: " *";
        color: red;
    }
    .card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    }
    .bom-item {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        position: relative;
    }
    .bom-item .remove-bom {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
    }
    .bom-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #dee2e6;
    }
    .bom-number {
        background-color: #0d6efd;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    #bom-container .bom-item:nth-child(odd) {
        background-color: #f8f9fa;
    }
    #bom-container .bom-item:nth-child(even) {
        background-color: #e9ecef;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Create New Job</h3>
                <p class="text-subtitle text-muted">Add a new production job with bill of materials</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('jobs.index') }}">Jobs</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <form action="{{ route('jobs.store') }}" method="POST" id="job-form">
            @csrf

            <div class="row">
                <div class="col-12">
                    <!-- Job Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="card-title">Job Information</h4>
                        </div>
                        <div class="card-body">
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="job_number" class="form-label required">Job Number</label>
                                    <input type="text" class="form-control @error('job_number') is-invalid @enderror"
                                           id="job_number" name="job_number" value="{{ old('job_number') }}" required>
                                    @error('job_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="job_name" class="form-label required">Job Name</label>
                                    <input type="text" class="form-control @error('job_name') is-invalid @enderror"
                                           id="job_name" name="job_name" value="{{ old('job_name') }}" required>
                                    @error('job_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="product_name" class="form-label required">Product Name</label>
                                    <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                                           id="product_name" name="product_name" value="{{ old('product_name') }}" required>
                                    @error('product_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="form-label required">Quantity</label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                           id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label required">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="planned" {{ old('status') == 'planned' ? 'selected' : '' }}>Planned</option>
                                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="start_date" class="form-label required">Start Date</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="due_date" class="form-label required">Due Date</label>
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                           id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Bill of Materials -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Bill of Materials (BOM)</h4>
                            <button type="button" class="btn btn-primary btn-sm" id="add-bom">
                                <i class="bi bi-plus-circle me-1"></i> Add Material
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="bom-container">
                                @if(old('boms'))
                                    @foreach(old('boms') as $index => $bom)
                                        <div class="bom-item" data-index="{{ $index }}">
                                            <div class="bom-header">
                                                <div class="d-flex align-items-center">
                                                    <div class="bom-number me-2">{{ $index + 1 }}</div>
                                                    <strong>Material Item</strong>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-danger remove-bom">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required">Material</label>
                                                    <select class="form-select material-select @error('boms.'.$index.'.material_id') is-invalid @enderror"
                                                            name="boms[{{ $index }}][material_id]" required>
                                                        <option value="">Select Material</option>
                                                        @foreach($materials as $material)
                                                            <option value="{{ $material->id }}"
                                                                    data-unit="{{ $material->unit }}"
                                                                    {{ old('boms.'.$index.'.material_id') == $material->id ? 'selected' : '' }}>
                                                                {{ $material->code }} - {{ $material->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('boms.'.$index.'.material_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required">Department</label>
                                                    <select class="form-select @error('boms.'.$index.'.department_id') is-invalid @enderror"
                                                            name="boms[{{ $index }}][department_id]" required>
                                                        <option value="">Select Department</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department->id }}"
                                                                    {{ old('boms.'.$index.'.department_id') == $department->id ? 'selected' : '' }}>
                                                                {{ $department->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('boms.'.$index.'.department_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required">Quantity Required</label>
                                                    <input type="number" step="0.01"
                                                           class="form-control @error('boms.'.$index.'.quantity_required') is-invalid @enderror"
                                                           name="boms[{{ $index }}][quantity_required]"
                                                           value="{{ old('boms.'.$index.'.quantity_required') }}"
                                                           min="0.01" required>
                                                    @error('boms.'.$index.'.quantity_required')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label required">Unit</label>
                                                    <input type="text"
                                                           class="form-control unit-input @error('boms.'.$index.'.unit') is-invalid @enderror"
                                                           name="boms[{{ $index }}][unit]"
                                                           value="{{ old('boms.'.$index.'.unit') }}"
                                                           readonly required>
                                                    @error('boms.'.$index.'.unit')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Notes</label>
                                                    <textarea class="form-control" name="boms[{{ $index }}][notes]" rows="2">{{ old('boms.'.$index.'.notes') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-2">No materials added yet. Click "Add Material" to start.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('jobs.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Create Job
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

<!-- BOM Template -->
<template id="bom-template">
    <div class="bom-item" data-index="">
        <div class="bom-header">
            <div class="d-flex align-items-center">
                <div class="bom-number me-2"></div>
                <strong>Material Item</strong>
            </div>
            <button type="button" class="btn btn-sm btn-danger remove-bom">
                <i class="bi bi-trash"></i>
            </button>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Material</label>
                <select class="form-select material-select" name="boms[][material_id]" required>
                    <option value="">Select Material</option>
                    @foreach($materials as $material)
                        <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">
                            {{ $material->code }} - {{ $material->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Department</label>
                <select class="form-select" name="boms[][department_id]" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Quantity Required</label>
                <input type="number" step="0.01" class="form-control"
                       name="boms[][quantity_required]" min="0.01" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Unit</label>
                <input type="text" class="form-control unit-input" name="boms[][unit]" readonly required>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="boms[][notes]" rows="2"></textarea>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
    let bomIndex = {{ old('boms') ? count(old('boms')) : 0 }};

    // Add BOM
    document.getElementById('add-bom').addEventListener('click', function() {
        const template = document.getElementById('bom-template');
        const clone = template.content.cloneNode(true);
        const bomItem = clone.querySelector('.bom-item');

        // Update index
        bomItem.dataset.index = bomIndex;
        bomItem.querySelector('.bom-number').textContent = bomIndex + 1;

        // Update names
        bomItem.querySelectorAll('[name^="boms[]"]').forEach(el => {
            el.name = el.name.replace('[]', `[${bomIndex}]`);
        });

        const container = document.getElementById('bom-container');
        const emptyState = container.querySelector('.text-center.text-muted');
        if (emptyState) {
            emptyState.remove();
        }

        container.appendChild(clone);
        bomIndex++;

        updateBomNumbers();
    });

    // Remove BOM
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-bom')) {
            const bomItem = e.target.closest('.bom-item');
            bomItem.remove();
            updateBomNumbers();

            // Show empty state if no items
            const container = document.getElementById('bom-container');
            if (container.children.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p class="mt-2">No materials added yet. Click "Add Material" to start.</p>
                    </div>
                `;
            }
        }
    });

    // Auto-fill unit when material is selected
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('material-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const unit = selectedOption.dataset.unit || '';
            const bomItem = e.target.closest('.bom-item');
            const unitInput = bomItem.querySelector('.unit-input');
            unitInput.value = unit;
        }
    });

    // Update BOM numbers
    function updateBomNumbers() {
        const bomItems = document.querySelectorAll('.bom-item');
        bomItems.forEach((item, index) => {
            item.querySelector('.bom-number').textContent = index + 1;
            item.dataset.index = index;

            // Update input names
            item.querySelectorAll('[name^="boms["]').forEach(input => {
                const nameParts = input.name.match(/boms\[\d+\](\[.+\])/);
                if (nameParts) {
                    input.name = `boms[${index}]${nameParts[1]}`;
                }
            });
        });
    }

    // Validate due date is after start date
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = this.value;
        const dueDateInput = document.getElementById('due_date');
        dueDateInput.setAttribute('min', startDate);

        if (dueDateInput.value && dueDateInput.value < startDate) {
            dueDateInput.value = startDate;
        }
    });

    // Set initial min for due date
    window.addEventListener('DOMContentLoaded', function() {
        const startDate = document.getElementById('start_date').value;
        if (startDate) {
            document.getElementById('due_date').setAttribute('min', startDate);
        }
    });
</script>
@endpush
