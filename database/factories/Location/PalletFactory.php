<?php

namespace Database\Factories\Location;

use App\Models\Location\Pallet;
use App\Models\Location\ShelfLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location\Pallet>
 */
class PalletFactory extends Factory
{
    protected $model = Pallet::class;

    public function definition(): array
    {
        $maxWeight = $this->faker->randomFloat(2, 50, 2000);
        $currentWeight = $this->faker->randomFloat(2, 0, $maxWeight);
        
        return [
            'code' => $this->faker->unique()->regexify('P[0-9]{4}'),
            'name' => $this->faker->randomElement([
                'پالت اصلی',
                'پالت فرعی',
                'پالت دسترسی',
                'پالت بارگیری',
            ]),
            'pallet_type' => $this->faker->randomElement([
                'small',
                'large',
                'standard',
                'euro_pallet',
                'american_pallet',
                'custom',
            ]),
            'length' => $this->faker->randomFloat(2, 0.5, 3),
            'width' => $this->faker->randomFloat(2, 0.5, 2),
            'height' => $this->faker->randomFloat(2, 0.1, 1.5),
            'max_weight' => $maxWeight,
            'current_weight' => $currentWeight,
            'shelf_level_id' => ShelfLevel::factory(),
            'current_position' => $this->faker->regexify('[A-Z]{1}[0-9]{2}-R[0-9]{3}-L[0-9]{3}'),
            'status' => $this->faker->randomElement([
                'available',
                'occupied',
                'maintenance',
                'damaged',
            ]),
            'description' => $this->faker->paragraph(),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    public function small(): static
    {
        return $this->state(fn (array $attributes) => [
            'pallet_type' => 'small',
            'length' => $this->faker->randomFloat(2, 0.5, 1),
            'width' => $this->faker->randomFloat(2, 0.5, 1),
            'height' => $this->faker->randomFloat(2, 0.1, 0.5),
            'max_weight' => $this->faker->randomFloat(2, 50, 200),
            'name' => 'پالت کوچک',
        ]);
    }

    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'pallet_type' => 'large',
            'length' => $this->faker->randomFloat(2, 2, 3),
            'width' => $this->faker->randomFloat(2, 1.5, 2),
            'height' => $this->faker->randomFloat(2, 0.5, 1.5),
            'max_weight' => $this->faker->randomFloat(2, 1000, 2000),
            'name' => 'پالت بزرگ',
        ]);
    }

    public function euroPallet(): static
    {
        return $this->state(fn (array $attributes) => [
            'pallet_type' => 'euro_pallet',
            'length' => 1.2,
            'width' => 0.8,
            'height' => 0.144,
            'max_weight' => $this->faker->randomFloat(2, 500, 1500),
            'name' => 'پالت اروپایی',
        ]);
    }

    public function americanPallet(): static
    {
        return $this->state(fn (array $attributes) => [
            'pallet_type' => 'american_pallet',
            'length' => 1.219,
            'width' => 1.016,
            'height' => 0.144,
            'max_weight' => $this->faker->randomFloat(2, 500, 1500),
            'name' => 'پالت آمریکایی',
        ]);
    }

    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'available',
            'current_weight' => 0,
        ]);
    }

    public function occupied(): static
    {
        return $this->state(function (array $attributes) {
            $maxWeight = $attributes['max_weight'] ?? $this->faker->randomFloat(2, 50, 2000);
            $currentWeight = $this->faker->randomFloat(2, $maxWeight * 0.5, $maxWeight);
            
            return [
                'status' => 'occupied',
                'current_weight' => $currentWeight,
            ];
        });
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
        ]);
    }

    public function damaged(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'damaged',
        ]);
    }
}
