<?php

namespace App\Models\Location;

use App\Enums\RegionType;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Region extends Model
{
    protected $casts = [
        'type' => RegionType::class,
    ];
    protected $fillable = [
        'parent_id',
        'type',
        'name',
        'code',
        'description',
        'ordering',
        'is_active',
        'lat',
        'lon',
        'height',
        'central_address',
        'central_postal_code',
        'central_phone',
        'central_mobile',
        'central_email',
        'central_fax',
        'national_hub_region_id',
    ];

    public function provinceSupporters(): BelongsToMany
    {
        return $this->belongsToMany(
            Region::class,
            'region_province_supporters',
            'region_id',
            'province_supporter_id'
        );
    }

    protected function casts(): array
    {
        return [
            'type' => RegionType::class,
        ];
    }

    // Scopes

    #[Scope]
    protected function ofType(Builder $query, string $type): void
    {
        $query->where('type', $type);
    }

    // Relationships

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_id')->with('children');
    }

    public function regionProvince(): HasOne
    {
        return $this->hasOne(RegionProvince::class);
    }

    public function regionTown(): HasOne
    {
        return $this->hasOne(RegionTown::class);
    }

    public function regionBranch(): HasOne
    {
        return $this->hasOne(RegionBranch::class);
    }

    public function regionDistrict(): HasOne
    {
        return $this->hasOne(RegionDistrict::class);
    }

    public function regionCity(): HasOne
    {
        return $this->hasOne(RegionCity::class);
    }

    public function regionRuralDistrict(): HasOne
    {
        return $this->hasOne(RegionCity::class);
    }

    public function regionVillage(): HasOne
    {
        return $this->hasOne(RegionVillage::class);
    }
}
