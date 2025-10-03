<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'pallet_type',
        'length',
        'width',
        'height',
        'max_weight',
        'current_weight',
        'shelf_level_id',
        'current_position',
        'status',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'length' => 'decimal:2',
            'width' => 'decimal:2',
            'height' => 'decimal:2',
            'max_weight' => 'decimal:2',
            'current_weight' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function getPalletTypeLabelAttribute(): string
    {
        return match($this->pallet_type) {
            'small' => 'کوچک',
            'large' => 'بزرگ',
            'standard' => 'استاندارد',
            'euro_pallet' => 'پالت اروپایی',
            'american_pallet' => 'پالت آمریکایی',
            'custom' => 'سفارشی',
            default => $this->pallet_type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'available' => 'در دسترس',
            'occupied' => 'اشغال شده',
            'maintenance' => 'تعمیر',
            'damaged' => 'آسیب دیده',
            default => $this->status,
        };
    }

    public function shelfLevel(): BelongsTo
    {
        return $this->belongsTo(ShelfLevel::class);
    }

    public function getFullLocationAttribute(): string
    {
        if ($this->shelfLevel) {
            return $this->shelfLevel->full_location;
        }
        return "پالت {$this->name} ({$this->code}) - موقعیت: {$this->current_position}";
    }

    public function getVolumeAttribute(): float
    {
        return $this->length * $this->width * $this->height;
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

    public function updateCurrentPosition(): void
    {
        if ($this->shelfLevel) {
            $this->current_position = "{$this->shelfLevel->rack->corridor->code}-{$this->shelfLevel->rack->code}-{$this->shelfLevel->code}";
        }
        $this->save();
    }

    public function moveToShelfLevel(ShelfLevel $shelfLevel): bool
    {
        // Check if shelf level can accommodate this pallet
        if ($shelfLevel->available_weight < $this->current_weight) {
            return false;
        }

        // Check if shelf level allows this product type
        if ($shelfLevel->allowed_product_type !== 'general' && 
            $shelfLevel->allowed_product_type !== $this->pallet_type) {
            return false;
        }

        // Move the pallet
        $this->shelf_level_id = $shelfLevel->id;
        $this->updateCurrentPosition();
        
        // Update shelf level weight
        $shelfLevel->current_weight += $this->current_weight;
        $shelfLevel->updateOccupancyStatus();
        $shelfLevel->save();

        return true;
    }
}
