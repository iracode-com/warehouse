<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    // Document types
    public const TYPE_RECEIPT = 'receipt';           // رسید کالا

    public const TYPE_ISSUE = 'issue';               // خروج کالا

    public const TYPE_TRANSFER = 'transfer';         // انتقال بین انبارها

    public const TYPE_ADJUSTMENT = 'adjustment';     // تعدیل موجودی

    // Status
    public const STATUS_DRAFT = 'draft';             // پیش‌نویس

    public const STATUS_APPROVED = 'approved';       // تایید شده

    public const STATUS_CANCELLED = 'cancelled';     // لغو شده

    // Party types
    public const PARTY_SUPPLIER = 'supplier';        // تامین‌کننده

    public const PARTY_CUSTOMER = 'customer';        // مشتری

    public const PARTY_OTHER = 'other';              // سایر

    protected $fillable = [
        'document_number',
        'document_type',
        'document_date',
        'source_warehouse_id',
        'destination_warehouse_id',
        'party_type',
        'party_name',
        'party_code',
        'party_address',
        'party_phone',
        'reference_number',
        'invoice_number',
        'invoice_date',
        'creator_id',
        'approver_id',
        'approved_at',
        'status',
        'description',
        'notes',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'final_amount',
        'attachments',
    ];

    protected function casts(): array
    {
        return [
            'document_date' => 'date',
            'invoice_date' => 'date',
            'approved_at' => 'datetime',
            'total_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'attachments' => 'array',
            'notes' => 'array',
        ];
    }

    /**
     * Get document type options
     */
    public static function getTypeOptions(): array
    {
        return [
            self::TYPE_RECEIPT => 'رسید کالا',
            self::TYPE_ISSUE => 'خروج کالا',
            self::TYPE_TRANSFER => 'انتقال بین انبارها',
            self::TYPE_ADJUSTMENT => 'تعدیل موجودی',
        ];
    }

    /**
     * Get status options
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'پیش‌نویس',
            self::STATUS_APPROVED => 'تایید شده',
            self::STATUS_CANCELLED => 'لغو شده',
        ];
    }

    /**
     * Get party type options
     */
    public static function getPartyTypeOptions(): array
    {
        return [
            self::PARTY_SUPPLIER => 'تامین‌کننده',
            self::PARTY_CUSTOMER => 'مشتری',
            self::PARTY_OTHER => 'سایر',
        ];
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return self::getTypeOptions()[$this->document_type] ?? $this->document_type;
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Get status color for badge
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_CANCELLED => 'danger',
            default => 'gray',
        };
    }

    /**
     * Get type color for badge
     */
    public function getTypeColorAttribute(): string
    {
        return match ($this->document_type) {
            self::TYPE_RECEIPT => 'success',
            self::TYPE_ISSUE => 'danger',
            self::TYPE_TRANSFER => 'info',
            self::TYPE_ADJUSTMENT => 'gray',
            default => 'gray',
        };
    }

    /**
     * Source warehouse relationship
     */
    public function sourceWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'source_warehouse_id');
    }

    /**
     * Destination warehouse relationship
     */
    public function destinationWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'destination_warehouse_id');
    }

    /**
     * Creator relationship
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Approver relationship
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Document items relationship
     */
    public function items(): HasMany
    {
        return $this->hasMany(DocumentItem::class);
    }

    /**
     * Scope for approved documents
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope for draft documents
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope by document type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('document_type', $type);
    }

    /**
     * Check if document can be edited
     */
    public function canBeEdited(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Check if document can be approved
     */
    public function canBeApproved(): bool
    {
        return $this->status === self::STATUS_DRAFT && $this->items()->count() > 0;
    }

    /**
     * Check if document can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_APPROVED]);
    }

    /**
     * Calculate totals from items
     */
    public function calculateTotals(): void
    {
        $this->total_amount = $this->items->sum('total_price');
        $this->tax_amount = $this->items->sum('tax_amount');
        $this->discount_amount = $this->items->sum('discount_amount');
        $this->final_amount = $this->items->sum('final_amount');
        $this->save();
    }
}
