<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'unit',
        'alternative_unit',
        'conversion_factor',
        'unit_price',
        'min_stock',
        'max_stock',
        'current_stock',
        'packing_unit',
        'capacity_per_pack',
        'lead_time_days',
        'storage_location',
        'supplier',
        'image',
        'is_active'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'conversion_factor' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function specifications()
    {
        return $this->hasMany(MaterialSpecification::class);
    }

    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isLowStock()
    {
        return $this->current_stock <= $this->min_stock;
    }

    public function stockPercentage()
    {
        $maxStock = (float) $this->max_stock;

        if ($maxStock <= 0) {
            return 0;
        }

        return ((float) $this->current_stock / $maxStock) * 100;
    }
}
