<?php

namespace App\Http\Services;

use App\Enums\RegionType;
use App\Models\Location\Region;
use App\Models\User\UserRegionalAccess;
use Illuminate\Support\Facades\Cache;

class UserRegionalHelper
{
    /**
     * Get user regions in [id => name] format
     *
     * @param int $userId
     * @param bool $includeChildren Whether to include child regions based on access_type
     * @return array
     */
    public static function getUserRegions(int $userId, string $regionType = RegionType::CITY->value, array|null $regionIds = null, bool $includeChildren = false): array
    {
        $isSuperAdmin = auth()->user()->userIsSuperAdmin();
        $userAccesses = UserRegionalAccess::with(['region', 'region.children'])
            ->where('user_id', $userId)
            ->when(is_array($regionIds), function ($query) use ($regionIds) {
                return $query->whereIn('region_id', $regionIds);
            })
            ->whereHas('region', fn($query) => $query->where('status', true))
            ->get();

        $regions = [];

        if ($isSuperAdmin || $regionType == RegionType::COUNTRY->value) {
            return Region::where('type', $regionType)
                ->where('status', true)
                ->when(is_array($regionIds), function ($query) use ($regionIds) {
                    return $query->whereIn('id', $regionIds);
                })
                ->get()
                ->pluck('name', 'id')
                ->toArray();
        }


        foreach ($userAccesses as $access) {
            $region = $access->region;

            if ($region->type->value != $regionType) {
                continue;
            }

            if ($includeChildren) {
                $regions += self::getRegionWithChildren($region, 1);
                // switch ($access->access_type) {
                //     case 'province':
                //         // Include all cities in this province
                //         $regions += self::getRegionWithChildren($region);
                //         break;

                //     case 'city':
                //         // Include specific city and its districts
                //         $regions += self::getRegionWithChildren($region, 1);
                //         break;

                //     case 'specific_cities':
                //         // Include only the specific region
                //         $regions[$region->id] = $region->name;
                //         break;
                // }
            } else {
                // Just include the direct region
                $regions[$region->id] = $region->name;
            }
        }

        return $regions;
    }

    /**
     * Get region with all its children recursively
     */
    protected static function getRegionWithChildren(Region $region, int $depth = null): array
    {
        $regions = [$region->id => $region->name];

        if ($depth === 0) {
            return $regions;
        }

        foreach ($region->children as $child) {
            $nextDepth = $depth === null ? null : $depth - 1;
            $regions += self::getRegionWithChildren($child, $nextDepth);
        }

        return $regions;
    }

    /**
     * Check if user has access to a specific region
     */
    public static function hasAccessToRegion(int $userId, int $regionId): bool
    {
        $userRegions = self::getUserRegions($userId);
        return isset($userRegions[$regionId]);
    }

    /**
     * Get user regions with hierarchy information
     */
    public static function getUserRegionsWithHierarchy(int $userId): array
    {
        $userAccesses = UserRegionalAccess::with(['region', 'region.parent'])
            ->where('user_id', $userId)
            ->get();

        $regions = [];

        foreach ($userAccesses as $access) {
            $region = $access->region;
            $regions[$region->id] = [
                'name' => $region->name,
                'type' => $region->type->value,
                'access_type' => $access->access_type,
                'parent' => $region->parent ? $region->parent->name : null
            ];
        }

        return $regions;
    }
}