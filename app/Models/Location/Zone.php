<?php

namespace App\Models\Location;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'zone_type',
        'capacity_cubic_meters',
        'capacity_pallets',
        'temperature',
        'description',
        'is_active',
        'warehouse_id',
    ];

    protected function casts(): array
    {
        return [
            'capacity_cubic_meters' => 'decimal:2',
            'capacity_pallets' => 'integer',
            'temperature' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function getZoneTypeLabelAttribute(): string
    {
        return match($this->zone_type) {
            'cold_storage' => 'ذخیره‌سازی سرد',
            'hot_storage' => 'ذخیره‌سازی گرم',
            'general' => 'عمومی',
            'hazardous_materials' => 'مواد خطرناک',
            'auto_parts' => 'لوازم یدکی خودرو',
            'emergency_supplies' => 'تجهیزات امدادی',
            'temporary' => 'موقت',
            default => $this->zone_type,
        };
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function corridors(): HasMany
    {
        return $this->hasMany(Corridor::class);
    }

    public function getFullLocationAttribute(): string
    {
        return "{$this->warehouse->title} - {$this->name} ({$this->code})";
    }
}
