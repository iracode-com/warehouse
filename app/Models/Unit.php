<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
        'code',
        'description',
        'category',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get product profiles using this unit as unit of measure
     */
    public function productProfilesUnitOfMeasure(): HasMany
    {
        return $this->hasMany(ProductProfile::class, 'unit_of_measure_id');
    }

    /**
     * Get product profiles using this unit as primary unit
     */
    public function productProfilesPrimaryUnit(): HasMany
    {
        return $this->hasMany(ProductProfile::class, 'primary_unit_id');
    }

    /**
     * Get product profiles using this unit as secondary unit
     */
    public function productProfilesSecondaryUnit(): HasMany
    {
        return $this->hasMany(ProductProfile::class, 'secondary_unit_id');
    }

    /**
     * Scope for active units
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
