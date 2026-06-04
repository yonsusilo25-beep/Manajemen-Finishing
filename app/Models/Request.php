<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number',
        'job_id',
        'department_id',
        'user_id',
        'request_type',
        // 'additional_reason',
        'request_date',
        'status',
        'notes',
        'approved_by',
        'approved_at',
        'completed_by',
        'completed_at'
    ];

    protected $casts = [
        'request_date' => 'date',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            $request->request_number = self::generateRequestNumber();
        });

        // Auto update job status
        static::updated(function ($request) {
            $request->updateJobStatus();
        });
    }

    public static function generateRequestNumber()
    {
        $date = now()->format('Ymd');
        $lastRequest = self::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastRequest ? intval(substr($lastRequest->request_number, -4)) + 1 : 1;

        return 'REQ-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    // Relations
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function items()
    {
        return $this->hasMany(RequestItem::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(RequestStatusHistory::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Helper: Get urgency from job
    public function getUrgencyAttribute()
    {
        return $this->job ? $this->job->auto_urgency : 'normal';
    }

    // Helper: Get urgency badge class
    public function getUrgencyBadgeClass()
    {
        return $this->job ? $this->job->urgency_badge_class : 'secondary';
    }

    // Helper: Get status badge class
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'draft' => 'secondary',
            'submitted' => 'info',
            'checking_stock' => 'warning',
            'approved' => 'success',
            'partial_approved' => 'warning',
            'ready_for_pickup' => 'primary',
            'completed' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'dark',
            default => 'secondary'
        };
    }

    // Helper: Update job status based on requests
    protected function updateJobStatus()
    {
        if (!$this->job) return;

        $job = $this->job;

        // Check if all department requests are completed
        $allCompleted = $job->requests()
            ->where('status', 'completed')
            ->count() === $job->boms()->count();

        if ($allCompleted && $job->status !== 'completed') {
            $job->update(['status' => 'completed']);
        } elseif ($this->status === 'approved' && $job->status === 'planned') {
            $job->update(['status' => 'in_progress']);
        }
    }
}
