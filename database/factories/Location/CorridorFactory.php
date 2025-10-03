<?php

namespace Database\Factories\Location;

use App\Models\Location\Corridor;
use App\Models\Location\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location\Corridor>
 */
class CorridorFactory extends Factory
{
    protected $model = Corridor::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z]{1}[0-9]{2}'),
            'name' => $this->faker->randomElement([
                'راهرو اصلی',
                'راهرو فرعی',
                'راهرو دسترسی',
                'راهرو بارگیری',
            ]),
            'zone_id' => Zone::factory(),
            'rack_count' => $this->faker->numberBetween(5, 20),
            'access_type' => $this->faker->randomElement([
                'pedestrian',
                'forklift',
                'crane',
                'mixed',
            ]),
            'width' => $this->faker->randomFloat(2, 2, 8),
            'length' => $this->faker->randomFloat(2, 10, 100),
            'height' => $this->faker->randomFloat(2, 3, 6),
            'description' => $this->faker->paragraph(),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    public function pedestrian(): static
    {
        return $this->state(fn (array $attributes) => [
            'access_type' => 'pedestrian',
            'width' => $this->faker->randomFloat(2, 1.5, 3),
            'name' => 'راهرو پیاده',
        ]);
    }

    public function forklift(): static
    {
        return $this->state(fn (array $attributes) => [
            'access_type' => 'forklift',
            'width' => $this->faker->randomFloat(2, 3, 6),
            'name' => 'راهرو لیفتراک',
        ]);
    }

    public function crane(): static
    {
        return $this->state(fn (array $attributes) => [
            'access_type' => 'crane',
            'width' => $this->faker->randomFloat(2, 4, 8),
            'height' => $this->faker->randomFloat(2, 5, 10),
            'name' => 'راهرو جرثقیل',
        ]);
    }
}
