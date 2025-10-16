<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_set_id',
        'item_id',
        'quantity',
        'coefficient',
        'unit_id',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'float',
        'coefficient' => 'float',
    ];

    /**
     * The set this item belongs to
     */
    public function productSet(): BelongsTo
    {
        return $this->belongsTo(ProductSet::class);
    }

    /**
     * The inventory item for this set item
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Unit of measurement for this item
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Calculate the effective quantity (quantity * coefficient)
     */
    public function getEffectiveQuantityAttribute(): float
    {
        return $this->quantity * $this->coefficient;
    }

    /**
     * Get available inventory items with expiry dates for the same product
     */
    public function getAvailableInventoryWithExpiry(int $warehouseId)
    {
        if (! $this->item) {
            return collect();
        }

        return Item::where('warehouse_id', $warehouseId)
            ->where('product_profile_id', $this->item->product_profile_id)
            ->whereNotNull('expiry_date')
            ->where('current_stock', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->get();
    }
}
