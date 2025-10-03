<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionBranch extends Model
{
    protected $fillable = [
        'region_id',
        'type',
        'is_center',
        'branch_name',
        'establishment_date',
        'phone_number',
        'fax_number',
        'hf_call_code',
        'vhf_call_code',
        'vhf_channel_code',
        'building_image',
        'building_door_image',
        'is_thursday_holiday',
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
