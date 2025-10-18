<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Base extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'type_operational_center',
        'account_type',
        'name',
        'coding_old',
        'coding',
        'three_digit_code_new',
        'activity_days',
        'date_activity_days',
        'type_ownership',
        'type_structure',
        'start_activity',
        'branch_type',
        'end_activity',
        'memory_martyr',
        'seasonal_type',
        'occasional_id',
        'three_digit_code',
        'license_state',
        'phone',
        'fixed_number',
        'mobile',
        'fax',
        'satellite_phone',
        'lat',
        'lon',
        'lat_deg',
        'lat_min',
        'lat_sec',
        'lon_deg',
        'lon_min',
        'lon_sec',
        'height',
        'city_border',
        'arena',
        'ayan',
        'img_header',
        'img_license',
        'bfile1',
        'bfile2',
        'address',
        'description',
        'postal_code',
        'place_payment',
        'type_personnel_emis',
        'distance_to_branch',
        'is_active',
        'status_emis',
        'status_equipment',
        'status_dims',
        'status_air_relief',
        'status_memberrcs',
        'status_emdadyar',
        'status_webgis',
        'raromis_id',
        'member_id',
        'emdadyar_id',
        'update_emdadyar_id',
        'not_conditions',
    ];

    protected $casts = [
        'city_border' => 'array',
        'type_operational_center' => 'integer',
        'account_type' => 'integer',
        'three_digit_code_new' => 'integer',
        'type_ownership' => 'integer',
        'type_structure' => 'integer',
        'seasonal_type' => 'integer',
        'three_digit_code' => 'integer',
        'license_state' => 'integer',
        'lat' => 'decimal:7',
        'lon' => 'decimal:7',
        'lat_deg' => 'integer',
        'lat_min' => 'integer',
        'lat_sec' => 'decimal:7',
        'lon_deg' => 'integer',
        'lon_min' => 'integer',
        'lon_sec' => 'decimal:7',
        'height' => 'integer',
        'place_payment' => 'integer',
        'type_personnel_emis' => 'integer',
        'distance_to_branch' => 'float',
        'is_active' => 'boolean',
        'status_emis' => 'boolean',
        'status_equipment' => 'boolean',
        'status_dims' => 'boolean',
        'status_air_relief' => 'boolean',
        'status_memberrcs' => 'boolean',
        'status_emdadyar' => 'boolean',
        'status_webgis' => 'boolean',
        'raromis_id' => 'integer',
        'member_id' => 'integer',
        'emdadyar_id' => 'integer',
        'update_emdadyar_id' => 'integer',
        'not_conditions' => 'boolean',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Location\Region::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDisplayNameAttribute(): string
    {
        // if (is_array($this->name) && isset($this->name['fa'])) {
        //     return $this->name['fa'];
        // }

        return $this->name ?? '';
    }
}
