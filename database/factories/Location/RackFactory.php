<?php

namespace Database\Factories\Location;

use App\Models\Location\Corridor;
use App\Models\Location\Rack;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location\Rack>
 */
class RackFactory extends Factory
{
    protected $model = Rack::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('R[0-9]{3}'),
            'name' => $this->faker->randomElement([
                'قفسه اصلی',
                'قفسه فرعی',
                'قفسه دسترسی',
                'قفسه بارگیری',
            ]),
            'corridor_id' => Corridor::factory(),
            'rack_type' => $this->faker->randomElement([
                'fixed',
                'movable',
                'pallet_rack',
                'shelving',
                'cantilever',
                'drive_in',
                'push_back',
            ]),
            'level_count' => $this->faker->numberBetween(2, 8),
            'capacity_per_level' => $this->faker->randomFloat(2, 100, 2000),
            'height' => $this->faker->randomFloat(2, 2, 8),
            'width' => $this->faker->randomFloat(2, 1, 3),
            'depth' => $this->faker->randomFloat(2, 0.5, 2),
            'max_weight' => $this->faker->randomFloat(2, 500, 5000),
            'description' => $this->faker->paragraph(),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    public function fixed(): static
    {
        return $this->state(fn (array $attributes) => [
            'rack_type' => 'fixed',
            'name' => 'قفسه ثابت',
        ]);
    }

    public function movable(): static
    {
        return $this->state(fn (array $attributes) => [
            'rack_type' => 'movable',
            'name' => 'قفسه متحرک',
        ]);
    }

    public function palletRack(): static
    {
        return $this->state(fn (array $attributes) => [
            'rack_type' => 'pallet_rack',
            'name' => 'قفسه پالت‌دار',
            'level_count' => $this->faker->numberBetween(3, 6),
            'capacity_per_level' => $this->faker->randomFloat(2, 500, 1500),
        ]);
    }

    public function heavyDuty(): static
    {
        return $this->state(fn (array $attributes) => [
            'max_weight' => $this->faker->randomFloat(2, 2000, 10000),
            'capacity_per_level' => $this->faker->randomFloat(2, 1000, 5000),
            'height' => $this->faker->randomFloat(2, 4, 10),
        ]);
    }
}
