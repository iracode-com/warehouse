<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Category extends Model
{
    use HasFactory;

    // Status constants
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_ARCHIVED = 'archived';

    // Hierarchy level constants
    public const LEVEL_MAIN = 1;
    public const LEVEL_SUB = 2;
    public const LEVEL_SUB_SUB = 3;
    public const LEVEL_ITEM = 4;

    protected $fillable = [
        'code',
        'name',
        'description',
        'hierarchy_level',
        'parent_id',
        'order_index',
        'status',
        'icon',
        'color',
        'metadata',
        'is_leaf',
        'full_path',
        'children_count',
        'items_count',
        'category_type',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'is_leaf' => 'boolean',
            'children_count' => 'integer',
            'items_count' => 'integer',
            'order_index' => 'integer',
            'hierarchy_level' => 'integer',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'فعال',
            self::STATUS_INACTIVE => 'غیرفعال',
            self::STATUS_ARCHIVED => 'آرشیو شده',
            default => $this->status,
        };
    }

    public function getHierarchyLevelLabelAttribute(): string
    {
        return match($this->hierarchy_level) {
            self::LEVEL_MAIN => 'دسته اصلی',
            self::LEVEL_SUB => 'زیر دسته',
            self::LEVEL_SUB_SUB => 'زیر زیر دسته',
            self::LEVEL_ITEM => 'کالا',
            default => 'نامشخص',
        };
    }

    // Static methods for options
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'فعال',
            self::STATUS_INACTIVE => 'غیرفعال',
            self::STATUS_ARCHIVED => 'آرشیو شده',
        ];
    }

    public static function getHierarchyLevelOptions(): array
    {
        return [
            self::LEVEL_MAIN => 'دسته اصلی',
            self::LEVEL_SUB => 'زیر دسته',
            self::LEVEL_SUB_SUB => 'زیر زیر دسته',
            self::LEVEL_ITEM => 'کالا',
        ];
    }

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order_index');
    }

    public function allChildren(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(CategoryAttribute::class);
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(CategoryAttributeValue::class);
    }

    public function rules(): HasMany
    {
        return $this->hasMany(Rule::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    public function productProfiles(): HasMany
    {
        return $this->hasMany(ProductProfile::class);
    }

    // Helper methods
    public function getFullPathAttribute(): string
    {
        $path = collect([$this->name]);
        $parent = $this->parent;

        while ($parent) {
            $path->prepend($parent->name);
            $parent = $parent->parent;
        }

        return $path->implode(' > ');
    }

    public function getBreadcrumbAttribute(): array
    {
        $breadcrumb = [];
        $current = $this;

        while ($current) {
            array_unshift($breadcrumb, [
                'id' => $current->id,
                'name' => $current->name,
                'code' => $current->code,
            ]);
            $current = $current->parent;
        }

        return $breadcrumb;
    }

    public function getDepthAttribute(): int
    {
        $depth = 0;
        $current = $this->parent;

        while ($current) {
            $depth++;
            $current = $current->parent;
        }

        return $depth;
    }

    public function isRoot(): bool
    {
        return $this->hierarchy_level === 1;
    }

    public function isLeaf(): bool
    {
        return $this->is_leaf || $this->children_count === 0;
    }

    public function hasChildren(): bool
    {
        return $this->children_count > 0;
    }

    public function getActiveChildren(): HasMany
    {
        return $this->children()->where('status', 'active');
    }

    public function getAllDescendants(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->with('allDescendants');
    }

    public function getAncestors(): array
    {
        $ancestors = [];
        $current = $this->parent;

        while ($current) {
            array_unshift($ancestors, $current);
            $current = $current->parent;
        }

        return $ancestors;
    }

    public function getSiblings(): HasMany
    {
        return $this->parent ? $this->parent->children() : Category::whereNull('parent_id');
    }

    public function moveTo(Category $newParent): bool
    {
        if ($this->id === $newParent->id || $this->isAncestorOf($newParent)) {
            return false;
        }

        $this->parent_id = $newParent->id;
        $this->hierarchy_level = $newParent->hierarchy_level + 1;
        $this->save();

        $this->updateFullPath();
        $this->updateChildrenPaths();

        return true;
    }

    public function isAncestorOf(Category $category): bool
    {
        $current = $category->parent;

        while ($current) {
            if ($current->id === $this->id) {
                return true;
            }
            $current = $current->parent;
        }

        return false;
    }

    public function updateFullPath(): void
    {
        $this->full_path = $this->getFullPathAttribute();
        $this->save();
    }

    public function updateChildrenPaths(): void
    {
        foreach ($this->children as $child) {
            $child->updateFullPath();
            $child->updateChildrenPaths();
        }
    }

    public function updateCounts(): void
    {
        $this->children_count = $this->children()->count();
        $this->items_count = $this->is_leaf ? 1 : 0; // This would be updated based on actual items
        $this->save();

        if ($this->parent) {
            $this->parent->updateCounts();
        }
    }

    // Scopes
    public function scopeRoots($query)
    {
        return $query->where('hierarchy_level', 1);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByLevel($query, int $level)
    {
        return $query->where('hierarchy_level', $level);
    }

    public function scopeLeaves($query)
    {
        return $query->where('is_leaf', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index')->orderBy('name');
    }
}
