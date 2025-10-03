<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hierarchyLevel = $this->faker->numberBetween(1, 4);
        $isLeaf = $hierarchyLevel === 4;

        return [
            'code' => $this->generateCode($hierarchyLevel),
            'name' => $this->generateName($hierarchyLevel),
            'description' => $this->faker->optional(0.7)->sentence(),
            'hierarchy_level' => $hierarchyLevel,
            'parent_id' => null, // Will be set in configure
            'order_index' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['active', 'inactive', 'archived']),
            'icon' => $this->faker->optional(0.3)->randomElement([
                'heroicon-o-cube',
                'heroicon-o-squares-2x2',
                'heroicon-o-map-pin',
                'heroicon-o-building-storefront',
                'heroicon-o-truck',
                'heroicon-o-wrench-screwdriver',
            ]),
            'color' => $this->faker->optional(0.5)->hexColor(),
            'metadata' => $this->faker->optional(0.3)->randomElements([
                'risk_level' => $this->faker->randomElement(['low', 'medium', 'high']),
                'storage_type' => $this->faker->randomElement(['cold', 'warm', 'ambient']),
                'priority' => $this->faker->numberBetween(1, 10),
            ], $this->faker->numberBetween(1, 3)),
            'is_leaf' => $isLeaf,
            'full_path' => null, // Will be set in configure
            'children_count' => $isLeaf ? 0 : $this->faker->numberBetween(0, 10),
            'items_count' => $isLeaf ? $this->faker->numberBetween(0, 100) : 0,
        ];
    }

    /**
     * Generate category code based on hierarchy level
     */
    protected function generateCode(int $level): string
    {
        $prefixes = ['MAIN', 'SUB', 'SUBSUB', 'ITEM'];
        $prefix = $prefixes[$level - 1];
        
        return $prefix . '-' . $this->faker->unique()->numberBetween(1000, 9999);
    }

    /**
     * Generate category name based on hierarchy level
     */
    protected function generateName(int $level): string
    {
        $names = [
            1 => [
                'امدادی',
                'اسقاط و مستعمل',
                'لوازم و قطعات یدکی',
                'آماده عملیات',
                'لوازم امداد هوایی',
                'تجهیزات امداد و نجات',
            ],
            2 => [
                'تجهیزات امداد و نجات',
                'لوازم امداد هوایی',
                'آماده عملیات',
                'چادر و سرپناه',
                'کیسه خواب و پتو',
                'ابزارهای پزشکی',
            ],
            3 => [
                'چادر خانوادگی',
                'چادر تک نفره',
                'پتو گرم',
                'کیسه خواب',
                'جعبه کمک‌های اولیه',
                'ماسک اکسیژن',
            ],
            4 => [
                'چادر خانوادگی 4 نفره',
                'چادر تک نفره استاندارد',
                'پتو گرم 2 متری',
                'کیسه خواب زمستانی',
                'جعبه کمک‌های اولیه کامل',
                'ماسک اکسیژن قابل حمل',
            ],
        ];

        $levelNames = $names[$level] ?? ['نام دسته‌بندی'];
        return $this->faker->randomElement($levelNames);
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Category $category) {
            // Set parent if not root level
            if ($category->hierarchy_level > 1) {
                $parent = Category::where('hierarchy_level', $category->hierarchy_level - 1)
                    ->inRandomOrder()
                    ->first();
                
                if ($parent) {
                    $category->update(['parent_id' => $parent->id]);
                }
            }

            // Update full path
            $category->updateFullPath();
        });
    }

    /**
     * Create a root category (level 1)
     */
    public function root(): static
    {
        return $this->state(fn (array $attributes) => [
            'hierarchy_level' => 1,
            'parent_id' => null,
            'is_leaf' => false,
        ]);
    }

    /**
     * Create a leaf category (level 4)
     */
    public function leaf(): static
    {
        return $this->state(fn (array $attributes) => [
            'hierarchy_level' => 4,
            'is_leaf' => true,
            'children_count' => 0,
        ]);
    }

    /**
     * Create an active category
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Create an inactive category
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
