<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionTown extends Model
{
    protected $fillable = [
        'region_id',
        'type',
        'is_center',
    ];

    public const TYPES = [
        0 => 'شهرستان',
        1 => 'ستاد',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
