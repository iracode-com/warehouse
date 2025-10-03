<?php

namespace App\Models\Location;

use App\Enums\RegionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Town extends Model
{
    use HasFactory;
    protected $table    = 'regions';
    protected static function booted(): void
    {
        static::addGlobalScope('region', function (Builder $builder) {
            $builder->where('type', RegionType::TOWN->value);
        });
    }
}
