<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShelfLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'rack_id',
        'level_number',
        'max_weight',
        'allowed_product_type',
        'occupancy_status',
        'current_weight',
        'height',
        'width',
        'depth',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'level_number' => 'integer',
            'max_weight' => 'decimal:2',
            'current_weight' => 'decimal:2',
            'height' => 'decimal:2',
            'width' => 'decimal:2',
            'depth' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function getAllowedProductTypeLabelAttribute(): string
    {
        return match($this->allowed_product_type) {
            'general' => 'عمومی',
            'hazardous' => 'مواد خطرناک',
            'auto_parts' => 'قطعات یدکی',
            'emergency_supplies' => 'تجهیزات امدادی',
            'fragile' => 'شکننده',
            'heavy_duty' => 'سنگین',
            'temperature_sensitive' => 'حساس به دما',
            default => $this->allowed_product_type,
        };
    }

    public function getOccupancyStatusLabelAttribute(): string
    {
        return match($this->occupancy_status) {
            'empty' => 'خالی',
            'partial' => 'نیمه‌پر',
            'full' => 'پر',
            default => $this->occupancy_status,
        };
    }

    public function rack(): BelongsTo
    {
        return $this->belongsTo(Rack::class);
    }

    public function pallets(): HasMany
    {
        return $this->hasMany(Pallet::class);
    }

    public function getFullLocationAttribute(): string
    {
        return "{$this->rack->corridor->zone->warehouse->title} - {$this->rack->corridor->zone->name} - {$this->rack->corridor->name} - {$this->rack->name} - {$this->name} ({$this->code})";
    }

    public function getVolumeAttribute(): float
    {
        return $this->height * $this->width * $this->depth;
    }

    public function getAvailableWeightAttribute(): float
    {
        return $this->max_weight - $this->current_weight;
    }

    public function getOccupancyPercentageAttribute(): float
    {
        if ($this->max_weight == 0) {
            return 0;
        }
        return ($this->current_weight / $this->max_weight) * 100;
    }

    public function updateOccupancyStatus(): void
    {
        $percentage = $this->occupancy_percentage;
        
        if ($percentage == 0) {
            $this->occupancy_status = 'empty';
        } elseif ($percentage >= 100) {
            $this->occupancy_status = 'full';
        } else {
            $this->occupancy_status = 'partial';
        }
        
        $this->save();
    }
}
