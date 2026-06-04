<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobBom extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'material_id',
        'department_id',
        'quantity_required',
        'unit',
        'sequence',
        'notes'
    ];

    protected $casts = [
        'quantity_required' => 'decimal:2',
    ];

    // Relations
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }

    // Helper: Check if this BOM item has been requested
    public function hasBeenRequested()
    {
        return $this->requestItems()->exists();
    }

    // Helper: Get total requested quantity
    public function getTotalRequestedQuantity()
    {
        return $this->requestItems()->sum('quantity_requested');
    }

    // Helper: Check if fully requested
    public function isFullyRequested()
    {
        $totalRequested = $this->getTotalRequestedQuantity();
        return $totalRequested >= $this->quantity_required;
    }
}
