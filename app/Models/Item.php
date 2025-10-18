<?php

namespace App\Models;

use App\Models\Location\Pallet;
use App\Models\Location\Rack;
use App\Models\Location\ShelfLevel;
use App\Models\Location\Zone;
use chillerlan\QRCode\QRCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Milon\Barcode\Facades\DNS1DFacade;

class Item extends Model
{
    use HasFactory;

    // Status constants
    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_DISCONTINUED = 'discontinued';

    public const STATUS_RECALLED = 'recalled';

    protected $fillable = [
        'product_profile_id',
        'source_document_id', // Added
        'serial_number',
        'barcode',
        'qr_code',
        'current_stock',
        'min_stock',
        'max_stock',
        'unit_cost',
        'selling_price',
        'status',
        'manufacture_date',
        'expiry_date',
        'production_date',
        'batch_number',
        'purchase_date',
        'warehouse_id',
        'zone_id',
        'rack_id',
        'shelf_level_id',
        'pallet_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'current_stock' => 'integer',
            'min_stock' => 'integer',
            'max_stock' => 'integer',
            'unit_cost' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'manufacture_date' => 'date',
            'expiry_date' => 'date',
            'purchase_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'فعال',
            self::STATUS_INACTIVE => 'غیرفعال',
            self::STATUS_DISCONTINUED => 'متوقف شده',
            self::STATUS_RECALLED => 'فراخوانی شده',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'success',
            self::STATUS_INACTIVE => 'warning',
            self::STATUS_DISCONTINUED => 'gray',
            self::STATUS_RECALLED => 'danger',
            default => 'gray',
        };
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->current_stock <= $this->min_stock) {
            return 'low_stock';
        } elseif ($this->max_stock && $this->current_stock >= $this->max_stock) {
            return 'overstock';
        } else {
            return 'normal';
        }
    }

    public function getStockStatusLabelAttribute(): string
    {
        return match ($this->stock_status) {
            'out_of_stock' => 'ناموجود',
            'low_stock' => 'موجودی کم',
            'overstock' => 'موجودی زیاد',
            'normal' => 'عادی',
            default => 'نامشخص',
        };
    }

    public function getStockStatusColorAttribute(): string
    {
        return match ($this->stock_status) {
            'out_of_stock' => 'danger',
            'low_stock' => 'warning',
            'overstock' => 'info',
            'normal' => 'success',
            default => 'gray',
        };
    }

    /**
     * Generate unique barcode for item
     */
    public static function generateBarcode(string $serialNumber): string
    {
        // تولید بارکد بر اساس شماره سریال
        return '3' . str_pad($serialNumber, 12, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique QR code for item
     */
    public static function generateQRCode(string $serialNumber): string
    {
        // تولید QR Code بر اساس شماره سریال
        return 'ITEM-' . $serialNumber . '-' . date('Ymd');
    }

    /**
     * Get barcode image
     */
    public function getBarcodeImageAttribute(): ?string
    {
        if (empty($this->barcode)) {
            return null;
        }

        try {
            $barcode = DNS1DFacade::getBarcodeJPG($this->barcode, 'C39+', 3, 40, array(0, 0, 0), true);
            return 'data:image/jpg;base64,' . $barcode;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get QR code image
     */
    public function getQrCodeImageAttribute(): ?string
    {
        if (empty($this->qr_code)) {
            return null;
        }

        try {
            $qrCode = new QRCode();
            return $qrCode->render($this->qr_code);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date > now() && $this->expiry_date->diffInDays() <= 30;
    }

    public function getFullLocationAttribute(): string
    {
        $location = [];

        if ($this->warehouse) {
            $location[] = $this->warehouse->title;
        }
        if ($this->zone) {
            $location[] = $this->zone->name;
        }
        if ($this->rack) {
            $location[] = $this->rack->name;
        }
        if ($this->shelfLevel) {
            $location[] = $this->shelfLevel->name;
        }
        if ($this->pallet) {
            $location[] = $this->pallet->name;
        }

        return implode(' > ', $location);
    }

    public function getNameAttribute(): string
    {
        return $this->productProfile?->name ?? 'نامشخص';
    }

    public function getSkuAttribute(): string
    {
        return $this->productProfile?->sku ?? 'نامشخص';
    }

    public function getBrandAttribute(): ?string
    {
        return $this->productProfile?->brand;
    }

    public function getModelAttribute(): ?string
    {
        return $this->productProfile?->model;
    }

    // Static methods for options
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'فعال',
            self::STATUS_INACTIVE => 'غیرفعال',
            self::STATUS_DISCONTINUED => 'متوقف شده',
            self::STATUS_RECALLED => 'فراخوانی شده',
        ];
    }

    // Relationships
    public function productProfile(): BelongsTo
    {
        return $this->belongsTo(ProductProfile::class);
    }

    public function sourceDocument(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'source_document_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function rack(): BelongsTo
    {
        return $this->belongsTo(Rack::class);
    }

    public function shelfLevel(): BelongsTo
    {
        return $this->belongsTo(ShelfLevel::class);
    }

    public function pallet(): BelongsTo
    {
        return $this->belongsTo(Pallet::class);
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(CategoryAttributeValue::class, 'item_id');
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class, 'item_id');
    }

    // Helper methods
    public function updateStock(int $quantity, string $operation = 'add'): void
    {
        if ($operation === 'add') {
            $this->increment('current_stock', $quantity);
        } elseif ($operation === 'subtract') {
            $this->decrement('current_stock', $quantity);
        } elseif ($operation === 'set') {
            $this->update(['current_stock' => $quantity]);
        }
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->min_stock;
    }

    public function isOutOfStock(): bool
    {
        return $this->current_stock <= 0;
    }

    public function isOverstock(): bool
    {
        return $this->max_stock && $this->current_stock >= $this->max_stock;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= min_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days));
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->whereHas('productProfile', function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        });
    }

    public function scopeByWarehouse($query, int $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeByLocation($query, ?int $zoneId = null, ?int $rackId = null, ?int $shelfLevelId = null)
    {
        if ($zoneId) {
            $query->where('zone_id', $zoneId);
        }
        if ($rackId) {
            $query->where('rack_id', $rackId);
        }
        if ($shelfLevelId) {
            $query->where('shelf_level_id', $shelfLevelId);
        }

        return $query;
    }

    public function scopeSearch($query, string $search)
    {
        return $query->whereHas('productProfile', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%")
                ->orWhere('model', 'like', "%{$search}%");
        })->orWhere('serial_number', 'like', "%{$search}%");
    }
}
