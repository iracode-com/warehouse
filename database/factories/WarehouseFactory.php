<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse>
 */
class WarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->company() . ' انبار',
            'manager_name' => fake()->name(),
            'manager_phone' => fake()->phoneNumber(),
            'usage_type' => fake()->randomElement(['emergency', 'scrap_used', 'auto_parts', 'ready_operations', 'air_rescue_parts', 'rescue_equipment', 'temporary']),
            'province' => fake()->randomElement(['تهران', 'اصفهان', 'شیراز', 'مشهد', 'تبریز', 'کرج', 'اهواز', 'قم', 'کرمان', 'ارومیه']),
            'branch' => fake()->randomElement(['شعبه مرکزی', 'شعبه شمال', 'شعبه جنوب', 'شعبه شرق', 'شعبه غرب']),
            'base' => fake()->randomElement(['پایگاه اصلی', 'پایگاه فرعی', 'پایگاه اضطراری']),
            'warehouse_info' => fake()->paragraph(),
            'establishment_year' => fake()->numberBetween(1350, 1400),
            'construction_year' => fake()->numberBetween(1350, 1400),
            'population_census' => fake()->numberBetween(1000, 100000),
            'ownership_type' => fake()->randomElement(['owned', 'rented', 'donated']),
            'area' => fake()->randomFloat(2, 100, 10000),
            'under_construction_area' => fake()->randomFloat(2, 0, 5000),
            'structure_type' => fake()->randomElement(['concrete', 'metal', 'prefabricated']),
            'warehouse_count' => fake()->numberBetween(1, 10),
            'small_inventory_count' => fake()->numberBetween(0, 1000),
            'large_inventory_count' => fake()->numberBetween(0, 500),
            'diesel_forklift_status' => fake()->randomElement(['healthy', 'defective']),
            'gasoline_forklift_status' => fake()->randomElement(['healthy', 'defective']),
            'gas_forklift_status' => fake()->randomElement(['healthy', 'defective']),
            'forklift_standard' => fake()->randomElement(['standard', 'deficit', 'surplus']),
            'ramp_length' => fake()->randomFloat(2, 5, 50),
            'ramp_height' => fake()->randomFloat(2, 1, 10),
            'warehouse_insurance' => fake()->randomElement(['yes', 'no']),
            'building_insurance' => fake()->randomElement(['yes', 'no']),
            'fire_suppression_system' => fake()->randomElement(['healthy', 'defective', 'installing']),
            'fire_alarm_system' => fake()->randomElement(['healthy', 'defective', 'installing']),
            'ram_rack' => fake()->randomElement(['yes', 'no']),
            'ram_rack_count' => fake()->numberBetween(0, 100),
            'cctv_system' => fake()->randomElement(['healthy', 'defective', 'installing']),
            'lighting_system' => fake()->randomElement(['healthy', 'defective']),
            'telephone' => fake()->randomElement(['yes', 'no']),
            'longitude' => fake()->longitude(),
            'latitude' => fake()->latitude(),
            'longitude_e' => fake()->longitude(),
            'latitude_n' => fake()->latitude(),
            'altitude' => fake()->randomFloat(2, 0, 3000),
            'address' => fake()->address(),
            'branch_establishment_year' => fake()->numberBetween(1350, 1400),
            'population_census_1395' => fake()->numberBetween(1000, 100000),
            'provincial_risk_percentage' => fake()->randomFloat(2, 0, 100),
            'approved_grade' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'warehouse_area' => fake()->randomFloat(2, 50, 5000),
            'gps_x' => fake()->longitude(),
            'gps_y' => fake()->latitude(),
            'keeper_name' => fake()->name(),
            'keeper_mobile' => fake()->phoneNumber(),
            'postal_address' => fake()->address(),
            'nearest_branch_1' => fake()->randomElement(['شعبه مرکزی', 'شعبه شمال', 'شعبه جنوب']),
            'distance_to_branch_1' => fake()->randomFloat(2, 1, 100),
            'nearest_branch_2' => fake()->randomElement(['شعبه شرق', 'شعبه غرب', 'شعبه مرکزی']),
            'distance_to_branch_2' => fake()->randomFloat(2, 1, 100),
        ];
    }
}
