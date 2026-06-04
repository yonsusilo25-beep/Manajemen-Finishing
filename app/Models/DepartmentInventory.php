<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'material_id',
        'current_stock',
        'unit',
        'min_stock',
        'max_stock',
        'reorder_point',
        'reorder_quantity',
        'location',
        'status',
        'last_counted_at',
    ];

    protected $casts = [
        'last_counted_at' => 'datetime',
        'current_stock' => 'integer',
        'min_stock' => 'integer',
        'max_stock' => 'integer',
        'reorder_point' => 'integer',
        'reorder_quantity' => 'integer',
    ];

    /**
     * Get the department that owns the inventory
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the material for this inventory
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Check if stock is below minimum
     */
    public function isBelowMinimum()
    {
        if ($this->min_stock === null) {
            return false;
        }
        return $this->current_stock < $this->min_stock;
    }

    /**
     * Check if stock is above maximum
     */
    public function isAboveMaximum()
    {
        if ($this->max_stock === null) {
            return false;
        }
        return $this->current_stock > $this->max_stock;
    }

    /**
     * Check if need reorder
     */
    public function needsReorder()
    {
        if ($this->reorder_point === null) {
            return false;
        }
        return $this->current_stock <= $this->reorder_point;
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'active' ? 'success' : 'danger';
    }
}
