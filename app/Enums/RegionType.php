<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RegionType: string implements HasLabel
{
    case COUNTRY        = 'country';
    case HEADQUARTER    = 'headquarter';
    case PROVINCE       = 'province';
    case BRANCH         = 'branch';
    case TOWN           = 'town';
    case DISTRICT       = 'district';
    case RURAL_DISTRICT = 'rural_district';
    case CITY           = 'city';
    case VILLAGE        = 'village';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::COUNTRY        => __('warehouse.region_types.country'),
            self::HEADQUARTER    => __('warehouse.region_types.headquarter'),
            self::PROVINCE       => __('warehouse.region_types.province'),
            self::TOWN           => __('warehouse.region_types.town'),
            self::BRANCH         => __('warehouse.region_types.branch'),
            self::DISTRICT       => __('warehouse.region_types.district'),
            self::RURAL_DISTRICT => __('warehouse.region_types.rural_district'),
            self::CITY           => __('warehouse.region_types.city'),
            self::VILLAGE        => __('warehouse.region_types.village'),
        };
    }
}