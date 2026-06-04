<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_number',
        'job_name',
        'product_name',
        'quantity',
        'status',
        'start_date',
        'due_date',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    // Relations
    public function boms()
    {
        return $this->hasMany(JobBom::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper: Get BOM for specific department
    public function bomsForDepartment($departmentId)
    {
        return $this->boms()->where('department_id', $departmentId)->get();
    }

    // Helper: Auto calculate urgency based on due_date
    public function getAutoUrgencyAttribute()
    {
        $daysLeft = now()->diffInDays($this->due_date, false);

        if ($daysLeft < 0) return 'overdue';
        if ($daysLeft <= 3) return 'urgent';
        if ($daysLeft <= 7) return 'high';
        if ($daysLeft <= 14) return 'normal';
        return 'low';
    }

    // Helper: Get urgency badge class
    public function getUrgencyBadgeClassAttribute()
    {
        return match($this->auto_urgency) {
            'overdue' => 'danger',
            'urgent' => 'danger',
            'high' => 'warning',
            'normal' => 'primary',
            'low' => 'secondary',
            default => 'secondary'
        };
    }

    // Helper: Get status badge class
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'planned' => 'info',
            'in_progress' => 'primary',
            'completed' => 'success',
            'cancelled' => 'dark',
            default => 'secondary'
        };
    }

    // Helper: Check if job can be requested
    public function canBeRequested()
    {
        return in_array($this->status, ['planned', 'in_progress']);
    }

    // Helper: Get completion percentage based on requests
    public function getCompletionPercentageAttribute()
    {
        $totalBomItems = $this->boms()->count();
        if ($totalBomItems === 0) return 0;

        $completedRequests = $this->requests()
            ->where('status', 'completed')
            ->count();

        return round(($completedRequests / $totalBomItems) * 100);
    }
}
