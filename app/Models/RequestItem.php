<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'job_bom_id',
        'material_id',
        'quantity_requested',
        'quantity_approved',
        'unit',
        'is_additional',
        'additional_reason',
        'specifications',
        'notes'
    ];

    protected $casts = [
        'quantity_requested' => 'decimal:2',
        'quantity_approved' => 'decimal:2',
        'is_additional' => 'boolean',
    ];

    // Relations
    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function jobBom()
    {
        return $this->belongsTo(JobBom::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    // Helper: Check if item is from BOM
    public function isFromBom()
    {
        return !is_null($this->job_bom_id);
    }

    // Helper: Get quantity difference from BOM
    public function getQuantityVarianceAttribute()
    {
        if (!$this->jobBom) return 0;
        return $this->quantity_requested - $this->jobBom->quantity_required;
    }

    // Helper: Check if quantity matches BOM
    public function matchesBomQuantity()
    {
        if (!$this->jobBom) return true;
        return $this->quantity_requested == $this->jobBom->quantity_required;
    }
}
