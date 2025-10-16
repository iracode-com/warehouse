<?php

namespace App\Models;

use App\Models\Location\Pallet;
use App\Models\Location\Rack;
use App\Models\Location\ShelfLevel;
use App\Models\Location\Zone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'product_profile_id',
        'quantity',
        'unit_id',
        'unit_price',
        'total_price',
        'discount_percentage',
        'discount_amount',
        'tax_percentage',
        'tax_amount',
        'final_amount',
        'batch_number',
        'expiry_date',
        'production_date',
        'zone_id',
        'rack_id',
        'shelf_level_id',
        'pallet_id',
        'notes',
        'item_images',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_percentage' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'expiry_date' => 'date',
            'production_date' => 'date',
            'item_images' => 'array',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Document relationship
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Product Profile relationship
     */
    public function productProfile(): BelongsTo
    {
        return $this->belongsTo(ProductProfile::class);
    }

    /**
     * Unit relationship
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Zone relationship
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Rack relationship
     */
    public function rack(): BelongsTo
    {
        return $this->belongsTo(Rack::class);
    }

    /**
     * Shelf level relationship
     */
    public function shelfLevel(): BelongsTo
    {
        return $this->belongsTo(ShelfLevel::class);
    }

    /**
     * Pallet relationship
     */
    public function pallet(): BelongsTo
    {
        return $this->belongsTo(Pallet::class);
    }

    /**
     * Calculate line totals
     */
    public function calculateTotals(): void
    {
        // محاسبه قیمت کل
        $this->total_price = (float) ($this->quantity * $this->unit_price);

        // محاسبه تخفیف
        if ($this->discount_percentage > 0) {
            $this->discount_amount = (float) (($this->total_price * $this->discount_percentage) / 100);
        }

        // قیمت بعد از تخفیف
        $priceAfterDiscount = $this->total_price - $this->discount_amount;

        // محاسبه مالیات
        if ($this->tax_percentage > 0) {
            $this->tax_amount = (float) (($priceAfterDiscount * $this->tax_percentage) / 100);
        }

        // مبلغ نهایی
        $this->final_amount = (float) ($priceAfterDiscount + $this->tax_amount);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate totals before saving
        static::saving(function ($item) {
            $item->calculateTotals();
        });

        // Recalculate document totals after save
        static::saved(function ($item) {
            $item->document->calculateTotals();
        });

        // Recalculate document totals after delete
        static::deleted(function ($item) {
            $item->document->calculateTotals();
        });
    }
}
