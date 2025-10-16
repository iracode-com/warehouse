<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'set_type',
        'is_active',
        'total_quantity',
        'unit_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_quantity' => 'integer',
    ];

    /**
     * Items that make up this set
     */
    public function items(): HasMany
    {
        return $this->hasMany(ProductSetItem::class);
    }

    /**
     * Unit of measurement for this set
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Scope for active sets
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if set can be built from warehouse inventory
     */
    public function canBuildFromWarehouse(int $warehouseId, int $quantity = 1): bool
    {
        foreach ($this->items as $setItem) {
            $requiredQuantity = $setItem->quantity * $setItem->coefficient * $quantity;
            $availableQuantity = $setItem->item->current_stock ?? 0;

            if ($availableQuantity < $requiredQuantity) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get total value of set based on item prices
     */
    public function getTotalValueAttribute(): float
    {
        return $this->items->sum(function ($setItem) {
            return ($setItem->quantity * $setItem->coefficient) * ($setItem->item->unit_price ?? 0);
        });
    }
}
