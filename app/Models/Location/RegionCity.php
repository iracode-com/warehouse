<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionCity extends Model
{
    protected $fillable = [
        'region_id',
        'type',
        'is_center',
        'radius',
        'residence_type',
        'residence_status',
        'way_status',
        'address',
        'district_code',
        'district_record_code',
    ];
    public const TYPES = [
        0 => 'غیرستادی',
        2 => 'ستادی',
    ];
    public const RESIDENCE_TYPES = [
        1 => 'جلگه ای',
        2 => 'کوهستانی',
        3 => 'جلگه ای وکوهستانی',
        4 => 'تپه ای',
    ];
    public const RESIDENCE_STATUSES = [
        1 => 'دائمی',
        2 => 'فصلی',
    ];
    public const WAY_TYPES = [
        1 => 'آسفالته',
        2 => 'شوسه',
        3 => 'خاکی',
        4 => 'راه آهن',
        5 => 'راه آبی',
        6 => 'مالرو',
    ];
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
