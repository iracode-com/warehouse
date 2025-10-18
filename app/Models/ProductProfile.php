<?php

namespace App\Models;

use chillerlan\QRCode\QRCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Milon\Barcode\Facades\DNS1DFacade;

class ProductProfile extends Model
{
    use HasFactory;

    // Status constants
    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_DISCONTINUED = 'discontinued';

    protected $fillable = [
        'sku',
        'name',
        'description',
        'category_id',
        'category_type',
        'packaging_type_id',
        'product_type',
        'brand_id',
        'model',
        'barcode',
        'qr_code',
        'weight',
        'length',
        'width',
        'height',
        'volume',
        'unit_of_measure',
        'unit_of_measure_id',
        'primary_unit',
        'primary_unit_id',
        'secondary_unit',
        'secondary_unit_id',
        'manufacturer',
        'country_of_origin',
        'shelf_life_days',
        'standard_cost',
        'pricing_method',
        'feature_1',
        'feature_2',
        'has_expiry_date',
        'consumption_status',
        'is_flammable',
        'has_return_policy',
        'product_address',
        'minimum_stock_by_location',
        'reorder_point_by_location',
        'has_technical_specs',
        'technical_specs',
        'has_storage_conditions',
        'storage_conditions',
        'has_inspection',
        'inspection_details',
        'has_similar_products',
        'similar_products',
        'estimated_value',
        'annual_inflation_rate',
        'related_warehouses',
        'status',
        'custom_attributes',
        'images',
        'documents',
        'specifications',
        'additional_description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'length' => 'decimal:2',
            'width' => 'decimal:2',
            'height' => 'decimal:2',
            'volume' => 'decimal:2',
            'shelf_life_days' => 'integer',
            'standard_cost' => 'decimal:2',
            'has_expiry_date' => 'boolean',
            'is_flammable' => 'boolean',
            'has_return_policy' => 'boolean',
            'has_technical_specs' => 'boolean',
            'has_storage_conditions' => 'boolean',
            'has_inspection' => 'boolean',
            'has_similar_products' => 'boolean',
            'estimated_value' => 'decimal:2',
            'annual_inflation_rate' => 'decimal:2',
            'minimum_stock_by_location' => 'array',
            'reorder_point_by_location' => 'array',
            'similar_products' => 'array',
            'related_warehouses' => 'array',
            'custom_attributes' => 'array',
            'images' => 'array',
            'documents' => 'array',
            'metadata' => 'array',
            'specifications' => 'array',
            'is_active' => 'boolean',
        ];
    }

    // Static methods for options
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'فعال',
            self::STATUS_INACTIVE => 'غیرفعال',
            self::STATUS_DISCONTINUED => 'متوقف شده',
        ];
    }

    public static function getCategoryTypeOptions(): array
    {
        return [
            'relief' => 'امدادی',
            'non_relief' => 'غیر امدادی',
            'equipment' => 'تجهیزاتی',
            'transport' => 'ترابری',
            'support' => 'پشتیبانی',
            'scrap' => 'اسقاطی',
            'defective' => 'معیوب',
            'assets' => 'اموال',
        ];
    }

    public static function getPackagingTypeOptions(): array
    {
        return [
            'carton' => 'کارتن',
            'shrink' => 'شیرینگ',
            'box' => 'جعبه',
        ];
    }

    public static function getProductTypeOptions(): array
    {
        return [
            'consumable' => 'مصرفی',
            'capital' => 'سرمایه‌ای',
        ];
    }

    public static function getPricingMethodOptions(): array
    {
        return [
            'FIFO' => 'FIFO (اول وارد، اول خارج)',
            'LIFO' => 'LIFO (آخر وارد، اول خارج)',
            'FEFO' => 'FEFO (اول منقضی، اول خارج)',
        ];
    }

    public static function getConsumptionStatusOptions(): array
    {
        return [
            'high_consumption' => 'پر مصرف',
            'strategic' => 'استراتژیک',
            'low_consumption' => 'کم مصرف',
            'stagnant' => 'راکد',
        ];
    }

    public static function getUnitOfMeasureOptions(): array
    {
        return [
            'piece' => 'عدد',
            'kg' => 'کیلوگرم',
            'gram' => 'گرم',
            'liter' => 'لیتر',
            'meter' => 'متر',
            'cm' => 'سانتی‌متر',
            'box' => 'جعبه',
            'pack' => 'بسته',
            'carton' => 'کارتن',
            'pallet' => 'پالت',
        ];
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'فعال',
            self::STATUS_INACTIVE => 'غیرفعال',
            self::STATUS_DISCONTINUED => 'متوقف شده',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'success',
            self::STATUS_INACTIVE => 'warning',
            self::STATUS_DISCONTINUED => 'gray',
            default => 'gray',
        };
    }

    public function getFullNameAttribute(): string
    {
        $parts = [$this->name];

        if ($this->brand) {
            $parts[] = "({$this->brand})";
        }

        if ($this->model) {
            $parts[] = "مدل {$this->model}";
        }

        return implode(' ', $parts);
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function unitOfMeasure(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_of_measure_id');
    }

    public function primaryUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'primary_unit_id');
    }

    public function secondaryUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'secondary_unit_id');
    }

    public function packagingType(): BelongsTo
    {
        return $this->belongsTo(PackagingType::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    // Helper methods
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

    public function getQrCodeImageAttribute(): ?string
    {
        if (! $this->qr_code) {
            return null;
        }

        // تولید QR code ساده به صورت SVG
        // $qrCode = $this->qr_code;
        // $size = 150;
        // $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 '.$size.' '.$size.'">';
        // $svg .= '<rect width="'.$size.'" height="'.$size.'" fill="white" stroke="black" stroke-width="1"/>';

        // // تولید الگوی QR code ساده
        // $cellSize = 5;
        // $cells = $size / $cellSize;

        // for ($i = 0; $i < $cells; $i++) {
        //     for ($j = 0; $j < $cells; $j++) {
        //         if (($i + $j) % 3 == 0 || ($i * $j) % 7 == 0) {
        //             $x = $i * $cellSize;
        //             $y = $j * $cellSize;
        //             $svg .= '<rect x="'.$x.'" y="'.$y.'" width="'.$cellSize.'" height="'.$cellSize.'" fill="black"/>';
        //         }
        //     }
        // }

        // // اضافه کردن مربع‌های گوشه
        // $cornerSize = 15;
        // $svg .= '<rect x="5" y="5" width="'.$cornerSize.'" height="'.$cornerSize.'" fill="black"/>';
        // $svg .= '<rect x="'.($size - $cornerSize - 5).'" y="5" width="'.$cornerSize.'" height="'.$cornerSize.'" fill="black"/>';
        // $svg .= '<rect x="5" y="'.($size - $cornerSize - 5).'" width="'.$cornerSize.'" height="'.$cornerSize.'" fill="black"/>';

        // $svg .= '</svg>';

        $img = (new QRCode)->render($this->qr_code);

        return $img;
    }

    public static function generateSKU(int $categoryId): string
    {
        // تولید کد کالا بر اساس دسته‌بندی و شماره ترتیبی
        $category = Category::find($categoryId);
        $categoryCode = $category ? str_pad($categoryId, 3, '0', STR_PAD_LEFT) : '000';

        // یافتن آخرین شماره ترتیبی در این دسته‌بندی
        $lastProduct = static::where('category_id', $categoryId)
            ->where('sku', 'like', $categoryCode.'%')
            ->orderBy('sku', 'desc')
            ->first();

        if ($lastProduct) {
            $lastNumber = (int) substr($lastProduct->sku, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $categoryCode.str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public static function generateBarcode(string $sku): string
    {
        // تولید بارکد بر اساس SKU
        return '2'.str_pad($sku, 12, '0', STR_PAD_LEFT);
    }

    public static function generateQRCode(string $sku): string
    {
        // تولید QR Code بر اساس SKU
        return 'QR'.$sku.date('Ymd');
    }

    public function getCustomAttribute(string $key, $default = null)
    {
        return $this->custom_attributes[$key] ?? $default;
    }

    public function setCustomAttribute(string $key, $value): void
    {
        $attributes = $this->custom_attributes ?? [];
        $attributes[$key] = $value;
        $this->update(['custom_attributes' => $attributes]);
    }

    public function getImageUrl(int $index = 0): ?string
    {
        $images = $this->images ?? [];

        return $images[$index] ?? null;
    }

    public function addImage(string $url): void
    {
        $images = $this->images ?? [];
        $images[] = $url;
        $this->update(['images' => $images]);
    }

    public function removeImage(int $index): void
    {
        $images = $this->images ?? [];
        unset($images[$index]);
        $this->update(['images' => array_values($images)]);
    }

    public function productSets(): HasMany
    {
        return $this->hasMany(ProductSet::class);
    }

    public function copyProduct(string $newName, ?string $newSku = null): static
    {
        $newProduct = $this->replicate();
        $newProduct->name = $newName;
        $newProduct->sku = $newSku ?: static::generateSKU($this->category_id);
        $newProduct->barcode = static::generateBarcode($newProduct->sku);
        $newProduct->qr_code = static::generateQRCode($newProduct->sku);
        $newProduct->created_at = now();
        $newProduct->updated_at = now();
        $newProduct->save();

        return $newProduct;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%")
                ->orWhere('model', 'like', "%{$search}%");
        });
    }
}
