<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionProvince extends Model
{
    protected $fillable = [
        'region_id',
        'phone_prefix',
        'hf_call_code',
        'server_ip',
        'border_points',
        'can_view_by_subsystems',
        'person_governor_id',
        'urban_deputy_id',
        'crisis_manager_id',
        'province_emergency_id',
        'province_roads_id',
        'province_police_id',
        'serial',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
    public function RegionProvincesCanViewBySubSystem()
    {
        return $this->hasOne(RegionProvincesCanViewBySubSystem::class);
    }

    public function provinceSupporter()
    {
        return $this->belongsTo(Region::class, 'province_supporter_id');
    }

    public function personGovernor(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'person_governor_id');
    }

    public function urbanDeputy(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'urban_deputy_id');
    }

    public function crisisManager(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'crisis_manager_id');
    }

    public function provinceEmergency(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'province_emergency_id');
    }

    public function provinceRoads(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'province_roads_id');
    }

    public function provincePolice(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'province_police_id');
    }
}
