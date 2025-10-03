<?php

namespace App\Models\Location;

use App\Enums\RegionType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class City extends Model
{
    // use SoftDeletes;

    protected $table    = 'regions';
    protected $fillable = ['province_id', 'name', 'name_en', 'lat', 'lon', 'status'];
    protected $casts    = ['status' => Status::class];

    protected static function booted(): void
    {
        static::addGlobalScope('region', function (Builder $builder) {
            $builder->where('type', RegionType::CITY->value);
        });
    }

    protected $appends = ['latitude', 'longitude'];

	public function getLatitudeAttribute($value)
	{
		return $this->lat;
	}

	public function getLongitudeAttribute($value)
	{
		return $this->lon;
	}

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
