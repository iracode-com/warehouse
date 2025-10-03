<?php

namespace App\Models\Location;

use App\Enums\RegionType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Country extends Model
{
    // use SoftDeletes;
    protected $table    = 'regions';
    protected $fillable = ['fips', 'iso', 'domain', 'fa_name', 'en_name', 'status'];
    protected $casts    = ['status' => Status::class];

    protected static function booted(): void
    {
        static::addGlobalScope('region', function (Builder $builder) {
            $builder->where('type', RegionType::COUNTRY->value);
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
}
