@extends('layouts.app')

@section('title', 'User Details')

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
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 3rem;
        color: white;
        background-color: rgba(255, 255, 255, 0.2);
        border: 4px solid rgba(255, 255, 255, 0.3);
        margin-bottom: 1rem;
    }
    .stat-card {
        text-align: center;
        padding: 1.5rem;
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        border: 1px solid #e9ecef;
        margin-bottom: 1rem;
    }
    .stat-card .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    .stat-card .stat-value {
        font-size: 1.5rem;
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
    .info-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-bottom: 1px solid #e9ecef;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-item i {
        font-size: 1.25rem;
        width: 30px;
        text-align: center;
        margin-right: 1rem;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>User Details</h3>
                <p class="text-subtitle text-muted">View complete user information</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-3 text-center text-md-start">
                    <div class="avatar-large mx-auto mx-md-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-start">
                    <h2 class="mb-2">{{ $user->name }}</h2>
                    <p class="mb-2">
                        <i class="bi bi-envelope me-2"></i>{{ $user->email }}
                        @if($user->email_verified_at)
                            <span class="badge bg-success ms-2">
                                <i class="bi bi-patch-check-fill me-1"></i>Verified
                            </span>
                        @endif
                    </p>
                    <p class="mb-0">
                        <span class="badge bg-{{
                            $user->role === 'admin' ? 'danger' :
                            ($user->role === 'supervisor' ? 'warning' :
                            ($user->role === 'manager' ? 'info' : 'light'))
                        }}">
                            <i class="bi bi-award me-1"></i>{{ ucwords($user->role) }}
                        </span>
                        @if($user->department)
                            <span class="badge bg-light text-dark ms-2">
                                <i class="bi bi-building me-1"></i>{{ $user->department->name }}
                            </span>
                        @endif
                    </p>
                </div>
                <div class="col-md-3 text-center text-md-end mt-3 mt-md-0">
                    @if($user->is_active)
                        <span class="badge bg-success" style="font-size: 1rem; padding: 0.5rem 1rem;">
                            <i class="bi bi-check-circle me-1"></i>Active
                        </span>
                    @else
                        <span class="badge bg-secondary" style="font-size: 1rem; padding: 0.5rem 1rem;">
                            <i class="bi bi-x-circle me-1"></i>Inactive
                        </span>
                    @endif
                    @if($user->id === auth()->id())
                        <br>
                        <span class="badge bg-info mt-2" style="font-size: 0.9rem;">
                            <i class="bi bi-person-fill me-1"></i>This is you
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="action-buttons d-flex flex-wrap">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Edit User
                    </a>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $user->is_active ? 'danger' : 'success' }}"
                                    onclick="return confirm('Toggle user status?')">
                                <i class="bi bi-{{ $user->is_active ? 'x-circle' : 'check-circle' }} me-1"></i>
                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i> Delete User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- User Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>User Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-label">Full Name</div>
                                <div class="detail-value">
                                    <i class="bi bi-person me-2 text-primary"></i>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Email Address</div>
                                <div class="detail-value">
                                    <i class="bi bi-envelope me-2 text-info"></i>
                                    {{ $user->email }}
                                    @if($user->email_verified_at)
                                        <i class="bi bi-patch-check-fill text-success ms-1" title="Verified"></i>
                                    @else
                                        <i class="bi bi-exclamation-circle text-warning ms-1" title="Not verified"></i>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Role</div>
                                <div class="detail-value">
                                    <span class="badge bg-{{
                                        $user->role === 'admin' ? 'danger' :
                                        ($user->role === 'supervisor' ? 'warning' :
                                        ($user->role === 'manager' ? 'info' : 'primary'))
                                    }}">
                                        <i class="bi bi-award me-1"></i>
                                        {{ ucwords($user->role) }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Department</div>
                                <div class="detail-value">
                                    @if($user->department)
                                        <i class="bi bi-building me-2 text-warning"></i>
                                        {{ $user->department->name }}
                                    @else
                                        <span class="text-muted">No department assigned</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Account Status</div>
                                <div class="detail-value">
                                    @if($user->is_active)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-x-circle me-1"></i>Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Email Verification</div>
                                <div class="detail-value">
                                    @if($user->email_verified_at)
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Verified on {{ $user->email_verified_at->format('d M Y') }}
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                        Not verified
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3 pt-3 border-top">
                            <div class="col-md-6">
                                <div class="detail-label">Member Since</div>
                                <div class="detail-value">
                                    <i class="bi bi-calendar-plus me-2 text-success"></i>
                                    {{ $user->created_at->format('d M Y, H:i') }}
                                    <small class="text-muted d-block">
                                        ({{ $user->created_at->diffForHumans() }})
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-label">Last Updated</div>
                                <div class="detail-value">
                                    <i class="bi bi-calendar-check me-2 text-info"></i>
                                    {{ $user->updated_at->format('d M Y, H:i') }}
                                    <small class="text-muted d-block">
                                        ({{ $user->updated_at->diffForHumans() }})
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Additional Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <i class="bi bi-shield-check text-primary"></i>
                            <div>
                                <strong>Security</strong>
                                <small class="text-muted d-block">
                                    Password last changed: {{ $user->updated_at->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-clock-history text-info"></i>
                            <div>
                                <strong>Account Age</strong>
                                <small class="text-muted d-block">
                                    {{ $user->created_at->diffInDays(now()) }} days old
                                </small>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-key text-warning"></i>
                            <div>
                                <strong>Access Level</strong>
                                <small class="text-muted d-block">
                                    {{ ucwords($user->role) }} privileges
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Quick Stats -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Quick Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="bi bi-calendar-check text-success"></i>
                            </div>
                            <div class="stat-value">{{ $user->created_at->diffInDays(now()) }}</div>
                            <div class="stat-label">Days Active</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="bi bi-shield-check text-primary"></i>
                            </div>
                            <div class="stat-value">{{ ucwords($user->role) }}</div>
                            <div class="stat-label">User Role</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="bi bi-{{ $user->is_active ? 'check-circle text-success' : 'x-circle text-danger' }}"></i>
                            </div>
                            <div class="stat-value">{{ $user->is_active ? 'Active' : 'Inactive' }}</div>
                            <div class="stat-label">Account Status</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil me-2"></i> Edit Profile
                            </a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('users.toggle-status', $user) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-{{ $user->is_active ? 'warning' : 'success' }} w-100"
                                            onclick="return confirm('Toggle user status?')">
                                        <i class="bi bi-{{ $user->is_active ? 'pause-circle' : 'play-circle' }} me-2"></i>
                                        {{ $user->is_active ? 'Suspend Account' : 'Activate Account' }}
                                    </button>
                                </form>
                            @endif
                            <a href="mailto:{{ $user->email }}" class="btn btn-outline-info">
                                <i class="bi bi-envelope me-2"></i> Send Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    console.log('User profile loaded: {{ $user->name }}');
</script>
@endpush
