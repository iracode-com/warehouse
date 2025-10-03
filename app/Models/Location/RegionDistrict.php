<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionDistrict extends Model
{
    protected $fillable = [
        'region_id',
        'type',
        'is_center',
        'district_code',
        'district_record_code',
    ];
    public const TYPES = [
        0 => 'غیرستادی',
        2 => 'ستادی',
    ];
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
