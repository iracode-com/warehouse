<?php

namespace Database\Factories\Location;

use App\Models\Location\Rack;
use App\Models\Location\ShelfLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location\ShelfLevel>
 */
class ShelfLevelFactory extends Factory
{
    protected $model = ShelfLevel::class;

    public function definition(): array
    {
        $maxWeight = $this->faker->randomFloat(2, 100, 2000);
        $currentWeight = $this->faker->randomFloat(2, 0, $maxWeight);
        
        return [
            'code' => $this->faker->unique()->regexify('L[0-9]{3}'),
            'name' => $this->faker->randomElement([
                'طبقه اول',
                'طبقه دوم',
                'طبقه سوم',
                'طبقه چهارم',
                'طبقه پنجم',
            ]),
            'rack_id' => Rack::factory(),
            'level_number' => $this->faker->numberBetween(1, 8),
            'max_weight' => $maxWeight,
            'allowed_product_type' => $this->faker->randomElement([
                'general',
                'hazardous',
                'auto_parts',
                'emergency_supplies',
                'fragile',
                'heavy_duty',
                'temperature_sensitive',
            ]),
            'occupancy_status' => $this->faker->randomElement([
                'empty',
                'partial',
                'full',
            ]),
            'current_weight' => $currentWeight,
            'height' => $this->faker->randomFloat(2, 0.5, 2),
            'width' => $this->faker->randomFloat(2, 1, 3),
            'depth' => $this->faker->randomFloat(2, 0.5, 2),
            'description' => $this->faker->paragraph(),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    public function empty(): static
    {
        return $this->state(fn (array $attributes) => [
            'occupancy_status' => 'empty',
            'current_weight' => 0,
        ]);
    }

    public function partial(): static
    {
        return $this->state(function (array $attributes) {
            $maxWeight = $attributes['max_weight'] ?? $this->faker->randomFloat(2, 100, 2000);
            $currentWeight = $this->faker->randomFloat(2, $maxWeight * 0.1, $maxWeight * 0.8);
            
            return [
                'occupancy_status' => 'partial',
                'current_weight' => $currentWeight,
            ];
        });
    }

    public function full(): static
    {
        return $this->state(function (array $attributes) {
            $maxWeight = $attributes['max_weight'] ?? $this->faker->randomFloat(2, 100, 2000);
            
            return [
                'occupancy_status' => 'full',
                'current_weight' => $maxWeight,
            ];
        });
    }

    public function forHazardousMaterials(): static
    {
        return $this->state(fn (array $attributes) => [
            'allowed_product_type' => 'hazardous',
            'name' => 'طبقه مواد خطرناک',
        ]);
    }

    public function forAutoParts(): static
    {
        return $this->state(fn (array $attributes) => [
            'allowed_product_type' => 'auto_parts',
            'name' => 'طبقه لوازم یدکی',
        ]);
    }
}
