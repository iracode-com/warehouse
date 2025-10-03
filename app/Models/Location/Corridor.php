<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Corridor extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'zone_id',
        'rack_count',
        'access_type',
        'width',
        'length',
        'height',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rack_count' => 'integer',
            'width' => 'decimal:2',
            'length' => 'decimal:2',
            'height' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function getAccessTypeLabelAttribute(): string
    {
        return match($this->access_type) {
            'pedestrian' => 'پیاده',
            'forklift' => 'لیفتراک',
            'crane' => 'جرثقیل',
            'mixed' => 'مختلط',
            default => $this->access_type,
        };
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function racks(): HasMany
    {
        return $this->hasMany(Rack::class);
    }

    public function getFullLocationAttribute(): string
    {
        return "{$this->zone->warehouse->title} - {$this->zone->name} - {$this->name} ({$this->code})";
    }

    public function getAreaAttribute(): float
    {
        return $this->width * $this->length;
    }
}
