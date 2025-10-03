<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rack extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'corridor_id',
        'rack_type',
        'level_count',
        'capacity_per_level',
        'height',
        'width',
        'depth',
        'max_weight',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'level_count' => 'integer',
            'capacity_per_level' => 'decimal:2',
            'height' => 'decimal:2',
            'width' => 'decimal:2',
            'depth' => 'decimal:2',
            'max_weight' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function getRackTypeLabelAttribute(): string
    {
        return match($this->rack_type) {
            'fixed' => 'ثابت',
            'movable' => 'متحرک',
            'pallet_rack' => 'پالت‌دار',
            'shelving' => 'قفسه‌بندی',
            'cantilever' => 'کنسول',
            'drive_in' => 'رانشی',
            'push_back' => 'فشاری',
            default => $this->rack_type,
        };
    }

    public function corridor(): BelongsTo
    {
        return $this->belongsTo(Corridor::class);
    }

    public function shelfLevels(): HasMany
    {
        return $this->hasMany(ShelfLevel::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(RackInspection::class);
    }

    public function getFullLocationAttribute(): string
    {
        return "{$this->corridor->zone->warehouse->title} - {$this->corridor->zone->name} - {$this->corridor->name} - {$this->name} ({$this->code})";
    }

    public function getVolumeAttribute(): float
    {
        return $this->height * $this->width * $this->depth;
    }

    public function getTotalCapacityAttribute(): float
    {
        return $this->capacity_per_level * $this->level_count;
    }

    public function getLatestInspectionAttribute()
    {
        return $this->inspections()->latest('inspection_date')->first();
    }
}
