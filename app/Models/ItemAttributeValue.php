<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'attribute_id',
        'string_value',
        'numeric_value',
        'date_value',
        'datetime_value',
        'boolean_value',
        'json_value',
    ];

    protected function casts(): array
    {
        return [
            'numeric_value' => 'decimal:4',
            'date_value' => 'date',
            'datetime_value' => 'datetime',
            'boolean_value' => 'boolean',
            'json_value' => 'array',
        ];
    }

    // Relationships
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(CategoryAttribute::class, 'attribute_id');
    }

    // Helper methods
    public function getValue()
    {
        switch ($this->attribute->type) {
            case CategoryAttribute::TYPE_TEXT:
            case CategoryAttribute::TYPE_TEXTAREA:
            case CategoryAttribute::TYPE_SELECT:
            case CategoryAttribute::TYPE_FILE:
            case CategoryAttribute::TYPE_IMAGE:
                return $this->string_value;
                
            case CategoryAttribute::TYPE_NUMBER:
                return $this->numeric_value;
                
            case CategoryAttribute::TYPE_DATE:
                return $this->date_value;
                
            case CategoryAttribute::TYPE_DATETIME:
                return $this->datetime_value;
                
            case CategoryAttribute::TYPE_BOOLEAN:
                return $this->boolean_value;
                
            case CategoryAttribute::TYPE_MULTISELECT:
            case CategoryAttribute::TYPE_JSON:
                return $this->json_value;
                
            default:
                return $this->string_value;
        }
    }

    public function setValue($value): void
    {
        // Clear all values first
        $this->string_value = null;
        $this->numeric_value = null;
        $this->date_value = null;
        $this->datetime_value = null;
        $this->boolean_value = null;
        $this->json_value = null;

        switch ($this->attribute->type) {
            case CategoryAttribute::TYPE_TEXT:
            case CategoryAttribute::TYPE_TEXTAREA:
            case CategoryAttribute::TYPE_SELECT:
            case CategoryAttribute::TYPE_FILE:
            case CategoryAttribute::TYPE_IMAGE:
                $this->string_value = $value;
                break;
                
            case CategoryAttribute::TYPE_NUMBER:
                $this->numeric_value = $value;
                break;
                
            case CategoryAttribute::TYPE_DATE:
                $this->date_value = $value;
                break;
                
            case CategoryAttribute::TYPE_DATETIME:
                $this->datetime_value = $value;
                break;
                
            case CategoryAttribute::TYPE_BOOLEAN:
                $this->boolean_value = $value;
                break;
                
            case CategoryAttribute::TYPE_MULTISELECT:
            case CategoryAttribute::TYPE_JSON:
                $this->json_value = $value;
                break;
                
            default:
                $this->string_value = $value;
                break;
        }
    }

    public function getFormattedValueAttribute(): string
    {
        $value = $this->getValue();
        
        if ($value === null) {
            return '-';
        }

        switch ($this->attribute->type) {
            case CategoryAttribute::TYPE_BOOLEAN:
                return $value ? 'بله' : 'خیر';
                
            case CategoryAttribute::TYPE_DATE:
                return $value ? $value->format('Y/m/d') : '-';
                
            case CategoryAttribute::TYPE_DATETIME:
                return $value ? $value->format('Y/m/d H:i') : '-';
                
            case CategoryAttribute::TYPE_MULTISELECT:
                if (is_array($value)) {
                    return implode(', ', $value);
                }
                return $value;
                
            case CategoryAttribute::TYPE_JSON:
                if (is_array($value)) {
                    return json_encode($value, JSON_UNESCAPED_UNICODE);
                }
                return $value;
                
            default:
                return (string) $value;
        }
    }

    // Scopes
    public function scopeByAttribute($query, int $attributeId)
    {
        return $query->where('attribute_id', $attributeId);
    }

    public function scopeByItem($query, int $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    public function scopeWithValue($query, $value)
    {
        return $query->where(function ($q) use ($value) {
            $q->where('string_value', $value)
              ->orWhere('numeric_value', $value)
              ->orWhere('date_value', $value)
              ->orWhere('datetime_value', $value)
              ->orWhere('boolean_value', $value);
        });
    }

    public function scopeWithStringValue($query, string $value)
    {
        return $query->where('string_value', 'like', "%{$value}%");
    }

    public function scopeWithNumericValue($query, $value)
    {
        return $query->where('numeric_value', $value);
    }

    public function scopeWithDateValue($query, $date)
    {
        return $query->where('date_value', $date);
    }

    public function scopeWithBooleanValue($query, bool $value)
    {
        return $query->where('boolean_value', $value);
    }
}
