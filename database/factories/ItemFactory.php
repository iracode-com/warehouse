<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\ProductProfile;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_profile_id' => ProductProfile::factory(),
            'serial_number' => $this->faker->unique()->bothify('SN-####-####'),
            'current_stock' => $this->faker->numberBetween(0, 1000),
            'min_stock' => $this->faker->numberBetween(0, 100),
            'max_stock' => $this->faker->numberBetween(100, 2000),
            'unit_cost' => $this->faker->randomFloat(2, 10, 1000),
            'selling_price' => $this->faker->randomFloat(2, 20, 2000),
            'status' => $this->faker->randomElement(['active', 'inactive', 'discontinued', 'recalled']),
            'manufacture_date' => $this->faker->optional()->dateTimeBetween('-2 years', 'now'),
            'expiry_date' => $this->faker->optional()->dateTimeBetween('now', '+2 years'),
            'purchase_date' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'warehouse_id' => Warehouse::factory(),
            'zone_id' => null,
            'rack_id' => null,
            'shelf_level_id' => null,
            'pallet_id' => null,
            'notes' => $this->faker->optional()->paragraph(),
            'is_active' => $this->faker->boolean(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
            'is_active' => false,
        ]);
    }

    public function withExpiryDate(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => $this->faker->dateTimeBetween('now', '+1 year'),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }
}
