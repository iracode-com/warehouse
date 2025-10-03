<?php

namespace Database\Factories\Location;

use App\Models\Location\Zone;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location\Zone>
 */
class ZoneFactory extends Factory
{
    protected $model = Zone::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{3}'),
            'name' => $this->faker->randomElement([
                'منطقه عمومی',
                'منطقه مواد خطرناک',
                'منطقه لوازم یدکی',
                'منطقه تجهیزات امدادی',
                'منطقه ذخیره‌سازی سرد',
                'منطقه ذخیره‌سازی گرم',
            ]),
            'warehouse_id' => Warehouse::factory(),
            'zone_type' => $this->faker->randomElement([
                'cold_storage',
                'hot_storage',
                'general',
                'hazardous_materials',
                'auto_parts',
                'emergency_supplies',
                'temporary',
            ]),
            'capacity_cubic_meters' => $this->faker->randomFloat(2, 100, 5000),
            'capacity_pallets' => $this->faker->numberBetween(50, 500),
            'temperature' => $this->faker->randomFloat(2, -20, 40),
            'description' => $this->faker->paragraph(),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    public function coldStorage(): static
    {
        return $this->state(fn (array $attributes) => [
            'zone_type' => 'cold_storage',
            'name' => 'منطقه ذخیره‌سازی سرد',
            'temperature' => $this->faker->randomFloat(2, -20, 5),
        ]);
    }

    public function hotStorage(): static
    {
        return $this->state(fn (array $attributes) => [
            'zone_type' => 'hot_storage',
            'name' => 'منطقه ذخیره‌سازی گرم',
            'temperature' => $this->faker->randomFloat(2, 25, 40),
        ]);
    }

    public function hazardousMaterials(): static
    {
        return $this->state(fn (array $attributes) => [
            'zone_type' => 'hazardous_materials',
            'name' => 'منطقه مواد خطرناک',
            'temperature' => $this->faker->randomFloat(2, 15, 25),
        ]);
    }
}
