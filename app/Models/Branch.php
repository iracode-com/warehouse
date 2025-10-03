<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = [
        'region_id',
        'parent_id',
        'branch_type',
        'name',
        'two_digit_code',
        'date_establishment',
        'phone',
        'fax',
        'vhf_address',
        'hf_address',
        'vhf_channel',
        'lat',
        'lon',
        'lat_deg',
        'lat_min',
        'lat_sec',
        'lon_deg',
        'lon_min',
        'lon_sec',
        'city_border',
        'height',
        'img_header',
        'img_building',
        'address',
        'description',
        'postal_code',
        'coding',
        'closed_thursday',
        'closure_date',
        'closure_document',
        'date_closed_thursday',
        'date_closed_thursday_end',
    ];

    protected $casts = [
        'name' => 'array',
        'city_border' => 'array',
        'closed_thursday' => 'boolean',
        'closure_date' => 'date',
        'lat' => 'decimal:7',
        'lon' => 'decimal:7',
        'lat_sec' => 'decimal:7',
        'lon_sec' => 'decimal:7',
    ];

    // Branch types
    const BRANCH_TYPE_COUNTY = 0;
    const BRANCH_TYPE_HEADQUARTERS = 1;
    const BRANCH_TYPE_BRANCH = 2;
    const BRANCH_TYPE_INDEPENDENT_OFFICE = 3;
    const BRANCH_TYPE_DEPENDENT_OFFICE = 4;
    const BRANCH_TYPE_URBAN_AREA = 5;

    public function getBranchTypeNameAttribute(): string
    {
        return match($this->branch_type) {
            self::BRANCH_TYPE_COUNTY => 'شهرستان',
            self::BRANCH_TYPE_HEADQUARTERS => 'ستاد',
            self::BRANCH_TYPE_BRANCH => 'شعبه',
            self::BRANCH_TYPE_INDEPENDENT_OFFICE => 'دفترنمایندگی مستقل',
            self::BRANCH_TYPE_DEPENDENT_OFFICE => 'دفترنمایندگی وابسته',
            self::BRANCH_TYPE_URBAN_AREA => 'مناطق شهری',
            default => 'نامشخص',
        };
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Branch::class, 'parent_id');
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }
}
