<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rule extends Model
{
    use HasFactory;

    // Rule type constants
    public const RULE_TYPE_NUMERIC = 'numeric';
    public const RULE_TYPE_DATE = 'date';
    public const RULE_TYPE_STRING = 'string';
    public const RULE_TYPE_BOOLEAN = 'boolean';
    public const RULE_TYPE_JSON = 'json';
    public const RULE_TYPE_CUSTOM = 'custom';

    // Condition type constants
    public const CONDITION_EQUALS = 'equals';
    public const CONDITION_NOT_EQUALS = 'not_equals';
    public const CONDITION_GREATER_THAN = 'greater_than';
    public const CONDITION_LESS_THAN = 'less_than';
    public const CONDITION_GREATER_EQUAL = 'greater_equal';
    public const CONDITION_LESS_EQUAL = 'less_equal';
    public const CONDITION_CONTAINS = 'contains';
    public const CONDITION_NOT_CONTAINS = 'not_contains';
    public const CONDITION_IN = 'in';
    public const CONDITION_NOT_IN = 'not_in';
    public const CONDITION_BETWEEN = 'between';
    public const CONDITION_NOT_BETWEEN = 'not_between';
    public const CONDITION_IS_NULL = 'is_null';
    public const CONDITION_IS_NOT_NULL = 'is_not_null';

    // Alert type constants
    public const ALERT_TYPE_INFO = 'info';
    public const ALERT_TYPE_WARNING = 'warning';
    public const ALERT_TYPE_ERROR = 'error';
    public const ALERT_TYPE_CRITICAL = 'critical';

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'attribute_id',
        'rule_type',
        'condition_type',
        'condition_value',
        'condition_values',
        'alert_type',
        'alert_title',
        'alert_message',
        'alert_recipients',
        'priority',
        'is_active',
        'is_realtime',
        'check_interval',
        'last_checked',
        'trigger_count',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'condition_values' => 'array',
            'alert_recipients' => 'array',
            'metadata' => 'array',
            'is_active' => 'boolean',
            'is_realtime' => 'boolean',
            'priority' => 'integer',
            'check_interval' => 'integer',
            'trigger_count' => 'integer',
            'last_checked' => 'datetime',
        ];
    }

    public function getRuleTypeLabelAttribute(): string
    {
        return match($this->rule_type) {
            self::RULE_TYPE_NUMERIC => 'عددی',
            self::RULE_TYPE_DATE => 'تاریخ',
            self::RULE_TYPE_STRING => 'متنی',
            self::RULE_TYPE_BOOLEAN => 'بولی',
            self::RULE_TYPE_JSON => 'JSON',
            self::RULE_TYPE_CUSTOM => 'سفارشی',
            default => $this->rule_type,
        };
    }

    public function getConditionTypeLabelAttribute(): string
    {
        return match($this->condition_type) {
            self::CONDITION_EQUALS => 'برابر با',
            self::CONDITION_NOT_EQUALS => 'مخالف',
            self::CONDITION_GREATER_THAN => 'بزرگتر از',
            self::CONDITION_LESS_THAN => 'کوچکتر از',
            self::CONDITION_GREATER_EQUAL => 'بزرگتر یا مساوی',
            self::CONDITION_LESS_EQUAL => 'کوچکتر یا مساوی',
            self::CONDITION_CONTAINS => 'شامل',
            self::CONDITION_NOT_CONTAINS => 'شامل نباشد',
            self::CONDITION_IN => 'در لیست',
            self::CONDITION_NOT_IN => 'خارج از لیست',
            self::CONDITION_BETWEEN => 'بین',
            self::CONDITION_NOT_BETWEEN => 'خارج از محدوده',
            self::CONDITION_IS_NULL => 'خالی باشد',
            self::CONDITION_IS_NOT_NULL => 'خالی نباشد',
            default => $this->condition_type,
        };
    }

    public function getAlertTypeLabelAttribute(): string
    {
        return match($this->alert_type) {
            self::ALERT_TYPE_INFO => 'اطلاعاتی',
            self::ALERT_TYPE_WARNING => 'هشدار',
            self::ALERT_TYPE_ERROR => 'خطا',
            self::ALERT_TYPE_CRITICAL => 'بحرانی',
            default => $this->alert_type,
        };
    }

    // Static methods for options
    public static function getRuleTypeOptions(): array
    {
        return [
            self::RULE_TYPE_NUMERIC => 'عددی',
            self::RULE_TYPE_DATE => 'تاریخ',
            self::RULE_TYPE_STRING => 'متنی',
            self::RULE_TYPE_BOOLEAN => 'بولی',
            self::RULE_TYPE_JSON => 'JSON',
            self::RULE_TYPE_CUSTOM => 'سفارشی',
        ];
    }

    public static function getConditionTypeOptions(): array
    {
        return [
            self::CONDITION_EQUALS => 'برابر با',
            self::CONDITION_NOT_EQUALS => 'مخالف',
            self::CONDITION_GREATER_THAN => 'بزرگتر از',
            self::CONDITION_LESS_THAN => 'کوچکتر از',
            self::CONDITION_GREATER_EQUAL => 'بزرگتر یا مساوی',
            self::CONDITION_LESS_EQUAL => 'کوچکتر یا مساوی',
            self::CONDITION_CONTAINS => 'شامل',
            self::CONDITION_NOT_CONTAINS => 'شامل نباشد',
            self::CONDITION_IN => 'در لیست',
            self::CONDITION_NOT_IN => 'خارج از لیست',
            self::CONDITION_BETWEEN => 'بین',
            self::CONDITION_NOT_BETWEEN => 'خارج از محدوده',
            self::CONDITION_IS_NULL => 'خالی باشد',
            self::CONDITION_IS_NOT_NULL => 'خالی نباشد',
        ];
    }

    public static function getAlertTypeOptions(): array
    {
        return [
            self::ALERT_TYPE_INFO => 'اطلاعاتی',
            self::ALERT_TYPE_WARNING => 'هشدار',
            self::ALERT_TYPE_ERROR => 'خطا',
            self::ALERT_TYPE_CRITICAL => 'بحرانی',
        ];
    }

    public function getAlertTypeColorAttribute(): string
    {
        return match($this->alert_type) {
            'info' => 'blue',
            'warning' => 'yellow',
            'error' => 'orange',
            'critical' => 'red',
            default => 'gray',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            1 => 'خیلی کم',
            2 => 'کم',
            3 => 'متوسط',
            4 => 'بالا',
            5 => 'خیلی بالا',
            6 => 'فوری',
            7 => 'بحرانی',
            8 => 'اضطراری',
            9 => 'فوق‌العاده',
            10 => 'بحرانی مطلق',
            default => 'نامشخص',
        };
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(CategoryAttribute::class, 'attribute_id');
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    // Helper methods
    public function isNumericRule(): bool
    {
        return $this->rule_type === 'numeric';
    }

    public function isDateRule(): bool
    {
        return $this->rule_type === 'date';
    }

    public function isStringRule(): bool
    {
        return $this->rule_type === 'string';
    }

    public function isBooleanRule(): bool
    {
        return $this->rule_type === 'boolean';
    }

    public function isJsonRule(): bool
    {
        return $this->rule_type === 'json';
    }

    public function isCustomRule(): bool
    {
        return $this->rule_type === 'custom';
    }

    public function needsCheck(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->is_realtime) {
            return true;
        }

        if (!$this->last_checked) {
            return true;
        }

        return $this->last_checked->addSeconds($this->check_interval)->isPast();
    }

    public function incrementTriggerCount(): void
    {
        $this->increment('trigger_count');
    }

    public function updateLastChecked(): void
    {
        $this->update(['last_checked' => now()]);
    }

    public function getConditionValue()
    {
        switch ($this->rule_type) {
            case 'numeric':
                return (float) $this->condition_value;
            case 'date':
                return $this->condition_value;
            case 'boolean':
                return (bool) $this->condition_value;
            case 'json':
                return $this->condition_values;
            default:
                return $this->condition_value;
        }
    }

    public function getConditionValuesArray(): array
    {
        return $this->condition_values ?? [];
    }

    public function getAlertRecipientsArray(): array
    {
        return $this->alert_recipients ?? [];
    }

    public function getMetadataArray(): array
    {
        return $this->metadata ?? [];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRealtime($query)
    {
        return $query->where('is_realtime', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('rule_type', $type);
    }

    public function scopeByAlertType($query, string $alertType)
    {
        return $query->where('alert_type', $alertType);
    }

    public function scopeByPriority($query, int $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', '>=', 7);
    }

    public function scopeNeedsCheck($query)
    {
        return $query->where(function ($q) {
            $q->where('is_realtime', true)
              ->orWhereNull('last_checked')
              ->orWhereRaw('last_checked < DATE_SUB(NOW(), INTERVAL check_interval SECOND)');
        });
    }

    public function scopeOrderedByPriority($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('created_at', 'desc');
    }
}
