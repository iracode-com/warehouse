<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseShed extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
        'length',
        'width',
        'height',
        'area',
        'volume',
        'structure_type',
        'roof_type',
        'foundation_type',
        'wall_material',
        'address',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'area' => 'decimal:2',
        'volume' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'status' => 'boolean',
    ];

    /**
     * Get the warehouses for this shed.
     */
    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class, 'shed_id');
    }
}
