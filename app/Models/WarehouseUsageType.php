<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseUsageType extends Model
{
    protected $fillable = [
        'warehouse_id',
        'usage_type',
    ];

    /**
     * Get the warehouse that owns this usage type.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
