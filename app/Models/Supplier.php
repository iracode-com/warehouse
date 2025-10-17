<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'entity_type',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'mobile',
        'address',
        'province_id',
        'city_id',
        'country_id',
        'postal_code',
        'website',
        'notes',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // Status constants
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_SUSPENDED = 'suspended';

    // Entity type constants
    public const ENTITY_INDIVIDUAL = 'individual';
    public const ENTITY_LEGAL = 'legal';

    // Payment terms constants
    public const PAYMENT_CASH = 'cash';
    public const PAYMENT_30_DAYS = '30_days';
    public const PAYMENT_60_DAYS = '60_days';
    public const PAYMENT_90_DAYS = '90_days';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'فعال',
            self::STATUS_INACTIVE => 'غیرفعال',
            self::STATUS_SUSPENDED => 'معلق',
        ];
    }

    public static function getEntityTypeOptions(): array
    {
        return [
            self::ENTITY_INDIVIDUAL => 'شخصیت حقیقی',
            self::ENTITY_LEGAL => 'شخصیت حقوقی',
        ];
    }

    public static function getPaymentTermsOptions(): array
    {
        return [
            self::PAYMENT_CASH => 'نقدی',
            self::PAYMENT_30_DAYS => '30 روزه',
            self::PAYMENT_60_DAYS => '60 روزه',
            self::PAYMENT_90_DAYS => '90 روزه',
        ];
    }


    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    public function getPaymentTermsLabelAttribute(): string
    {
        return self::getPaymentTermsOptions()[$this->payment_terms] ?? $this->payment_terms;
    }

    // Relationships
    public function province()
    {
        return $this->belongsTo(\App\Models\Location\Province::class);
    }

    public function city()
    {
        return $this->belongsTo(\App\Models\Location\City::class);
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\Location\Country::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'party_code', 'code')
            ->where('party_type', 'supplier');
    }

    public function items()
    {
        return $this->hasManyThrough(Item::class, Document::class, 'party_code', 'source_document_id')
            ->where('documents.party_type', 'supplier');
    }
}
