<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryAttribute extends Model
{
    use HasFactory;

    // Type constants
    public const TYPE_TEXT = 'text';
    public const TYPE_NUMBER = 'number';
    public const TYPE_DATE = 'date';
    public const TYPE_DATETIME = 'datetime';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_SELECT = 'select';
    public const TYPE_MULTISELECT = 'multiselect';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_FILE = 'file';
    public const TYPE_IMAGE = 'image';
    public const TYPE_JSON = 'json';

    protected $fillable = [
        'category_id',
        'name',
        'label',
        'type',
        'options',
        'default_value',
        'is_required',
        'is_searchable',
        'is_filterable',
        'order_index',
        'validation_rules',
        'help_text',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'validation_rules' => 'array',
            'is_required' => 'boolean',
            'is_searchable' => 'boolean',
            'is_filterable' => 'boolean',
            'is_active' => 'boolean',
            'order_index' => 'integer',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            self::TYPE_TEXT => 'متن',
            self::TYPE_NUMBER => 'عدد',
            self::TYPE_DATE => 'تاریخ',
            self::TYPE_DATETIME => 'تاریخ و زمان',
            self::TYPE_BOOLEAN => 'بله/خیر',
            self::TYPE_SELECT => 'انتخاب تکی',
            self::TYPE_MULTISELECT => 'انتخاب چندگانه',
            self::TYPE_TEXTAREA => 'متن طولانی',
            self::TYPE_FILE => 'فایل',
            self::TYPE_IMAGE => 'تصویر',
            self::TYPE_JSON => 'JSON',
            default => $this->type,
        };
    }

    // Static methods for options
    public static function getTypeOptions(): array
    {
        return [
            self::TYPE_TEXT => 'متن',
            self::TYPE_NUMBER => 'عدد',
            self::TYPE_DATE => 'تاریخ',
            self::TYPE_DATETIME => 'تاریخ و زمان',
            self::TYPE_BOOLEAN => 'بله/خیر',
            self::TYPE_SELECT => 'انتخاب تکی',
            self::TYPE_MULTISELECT => 'انتخاب چندگانه',
            self::TYPE_TEXTAREA => 'متن طولانی',
            self::TYPE_FILE => 'فایل',
            self::TYPE_IMAGE => 'تصویر',
            self::TYPE_JSON => 'JSON',
        ];
    }

    public function getIsRequiredLabelAttribute(): string
    {
        return $this->is_required ? 'بله' : 'خیر';
    }

    public function getIsSearchableLabelAttribute(): string
    {
        return $this->is_searchable ? 'بله' : 'خیر';
    }

    public function getIsFilterableLabelAttribute(): string
    {
        return $this->is_filterable ? 'بله' : 'خیر';
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(CategoryAttributeValue::class, 'attribute_id');
    }

    public function rules(): HasMany
    {
        return $this->hasMany(Rule::class, 'attribute_id');
    }

    // Helper methods
    public function getValidationRulesArray(): array
    {
        return $this->validation_rules ?? [];
    }

    public function getOptionsArray(): array
    {
        return $this->options ?? [];
    }

    public function isSelectType(): bool
    {
        return in_array($this->type, ['select', 'multiselect']);
    }

    public function isNumericType(): bool
    {
        return $this->type === 'number';
    }

    public function isDateType(): bool
    {
        return in_array($this->type, ['date', 'datetime']);
    }

    public function isBooleanType(): bool
    {
        return $this->type === 'boolean';
    }

    public function isTextType(): bool
    {
        return in_array($this->type, ['text', 'textarea']);
    }

    public function isFileType(): bool
    {
        return in_array($this->type, ['file', 'image']);
    }

    public function getDefaultValue()
    {
        if ($this->default_value === null) {
            return null;
        }

        switch ($this->type) {
            case 'number':
                return (float) $this->default_value;
            case 'boolean':
                return (bool) $this->default_value;
            case 'date':
                return $this->default_value;
            case 'datetime':
                return $this->default_value;
            case 'select':
            case 'multiselect':
                return $this->default_value;
            default:
                return $this->default_value;
        }
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeSearchable($query)
    {
        return $query->where('is_searchable', true);
    }

    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index')->orderBy('name');
    }
}
