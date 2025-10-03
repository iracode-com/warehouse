<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'attribute_id',
        'value',
        'json_value',
        'numeric_value',
        'date_value',
        'datetime_value',
        'boolean_value',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'json_value' => 'array',
            'numeric_value' => 'decimal:4',
            'date_value' => 'date',
            'datetime_value' => 'datetime',
            'boolean_value' => 'boolean',
        ];
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

    // Helper methods
    public function getValue()
    {
        switch ($this->attribute->type) {
            case 'number':
                return $this->numeric_value;
            case 'date':
                return $this->date_value;
            case 'datetime':
                return $this->datetime_value;
            case 'boolean':
                return $this->boolean_value;
            case 'select':
            case 'multiselect':
                return $this->json_value;
            case 'file':
            case 'image':
                return $this->file_path;
            default:
                return $this->value;
        }
    }

    public function setValue($value): void
    {
        switch ($this->attribute->type) {
            case 'number':
                $this->numeric_value = $value;
                break;
            case 'date':
                $this->date_value = $value;
                break;
            case 'datetime':
                $this->datetime_value = $value;
                break;
            case 'boolean':
                $this->boolean_value = (bool) $value;
                break;
            case 'select':
            case 'multiselect':
                $this->json_value = is_array($value) ? $value : [$value];
                break;
            case 'file':
            case 'image':
                $this->file_path = $value;
                break;
            default:
                $this->value = $value;
                break;
        }
    }

    public function getDisplayValue(): string
    {
        $value = $this->getValue();

        if ($value === null) {
            return '-';
        }

        switch ($this->attribute->type) {
            case 'boolean':
                return $value ? 'بله' : 'خیر';
            case 'date':
                return $value ? $value->format('Y/m/d') : '-';
            case 'datetime':
                return $value ? $value->format('Y/m/d H:i') : '-';
            case 'multiselect':
                return is_array($value) ? implode(', ', $value) : $value;
            case 'file':
            case 'image':
                return $value ? basename($value) : '-';
            default:
                return (string) $value;
        }
    }

    public function isEmpty(): bool
    {
        $value = $this->getValue();
        return $value === null || $value === '' || (is_array($value) && empty($value));
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    // Scopes
    public function scopeByAttribute($query, int $attributeId)
    {
        return $query->where('attribute_id', $attributeId);
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeWithValue($query)
    {
        return $query->where(function ($q) {
            $q->whereNotNull('value')
              ->orWhereNotNull('numeric_value')
              ->orWhereNotNull('date_value')
              ->orWhereNotNull('datetime_value')
              ->orWhereNotNull('boolean_value')
              ->orWhereNotNull('json_value')
              ->orWhereNotNull('file_path');
        });
    }

    public function scopeEmpty($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('value')
              ->whereNull('numeric_value')
              ->whereNull('date_value')
              ->whereNull('datetime_value')
              ->whereNull('boolean_value')
              ->whereNull('json_value')
              ->whereNull('file_path');
        });
    }
}
