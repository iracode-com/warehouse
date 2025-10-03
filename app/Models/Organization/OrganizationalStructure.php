<?php

namespace App\Models\Organization;

use App\Models\Personnel\Personnel;
use App\Models\Location\Region;
use App\Models\User\UserOrganizationalInformation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class OrganizationalStructure extends Model
{
    protected $table = 'organizational_structures';
    protected $fillable = ['organization_id', 'parent_id', 'order', 'name'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrganizationalStructure::class, 'parent_id');
    }

    /**
     * Get the children organizational structures.
     */
    public function children(): HasMany
    {
        return $this->hasMany(OrganizationalStructure::class, 'parent_id')->orderBy('order');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    /**
     * Get the province if region is a city.
     */
    public function getProvinceAttribute()
    {
        if ($this->region && $this->region->type === 'city') {
            return $this->region->parent;
        }
        if ($this->region && $this->region->type === 'headquarter') {
            return $this->region;
        }
        return null;
    }

    /**
     * Get all organizational structures by province.
     */
    public static function getByProvince($provinceId)
    {
        return self::whereHas('region', function ($query) use ($provinceId) {
            $query->where('id', $provinceId)
                ->orWhere('parent_id', $provinceId);
        })->get();
    }

    /**
     * Get all organizational structures by city.
     */
    public static function getByCity($cityId)
    {
        return self::where('region_id', $cityId)->get();
    }

    // Tree methods for basic functionality
    public function getTreeTitle(): string
    {
        return $this->title ?? $this->name;
    }

    public function getTreeIcon(): ?string
    {
        return 'heroicon-o-building-office';
    }

    public function isRoot(): bool
    {
        return is_null($this->parent_id) || $this->parent_id == -1;
    }

    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    public function getDepth(): int
    {
        $depth = 0;
        $parent = $this->parent;

        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }

        return $depth;
    }

    // Convert flat collection to tree structure
    public static function toTree(?Collection $structures = null): Collection
    {
        if (!$structures) {
            $structures = static::orderBy('order')->get();
        }

        $tree = collect();
        $lookup = $structures->keyBy('id');

        foreach ($structures as $structure) {
            if ($structure->isRoot()) {
                $tree->push($structure);
            } else {
                $parent = $lookup->get($structure->parent_id);
                if ($parent) {
                    if (!isset($parent->children)) {
                        $parent->children = collect();
                    }
                    $parent->children->push($structure);
                }
            }
        }
        return $tree;
    }

    // Get all possible parent options for a given organization
    public static function getParentOptions($organizationId, $excludeId = null): array
    {
        $query = static::where('organization_id', $organizationId);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->pluck('title', 'id')->prepend('Root', -1)->toArray();
    }
}
