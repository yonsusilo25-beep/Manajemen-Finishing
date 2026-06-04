<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialSpecification extends Model
{
    use HasFactory;

    protected $table="material_specifications";

    protected $fillable = [
        'material_id',
        'spec_name',
        'spec_value'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}

