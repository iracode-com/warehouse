<?php

namespace Database\Factories\Location;

use App\Models\Location\Rack;
use App\Models\Location\RackInspection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location\RackInspection>
 */
class RackInspectionFactory extends Factory
{
    protected $model = RackInspection::class;

    public function definition(): array
    {
        $inspectionDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $nextInspectionDate = $this->faker->dateTimeBetween($inspectionDate, '+6 months');
        
        return [
            'rack_id' => Rack::factory(),
            'inspector_id' => User::factory(),
            'inspection_date' => $inspectionDate,
            'safety_status' => $this->faker->randomElement([
                'standard',
                'needs_repair',
                'critical',
                'out_of_service',
            ]),
            'inspection_notes' => $this->faker->paragraph(),
            'issues_found' => $this->faker->optional(0.3)->randomElements([
                [
                    'issue' => 'شکستگی در قفسه',
                    'severity' => 'high',
                    'description' => 'شکستگی در قسمت بالایی قفسه مشاهده شد',
                ],
                [
                    'issue' => 'خوردگی فلز',
                    'severity' => 'medium',
                    'description' => 'خوردگی در پایه‌های قفسه',
                ],
                [
                    'issue' => 'شل بودن پیچ‌ها',
                    'severity' => 'low',
                    'description' => 'برخی پیچ‌ها شل شده‌اند',
                ],
            ], $this->faker->numberBetween(1, 3)),
            'next_inspection_date' => $nextInspectionDate,
            'requires_maintenance' => $this->faker->boolean(30),
        ];
    }

    public function standard(): static
    {
        return $this->state(fn (array $attributes) => [
            'safety_status' => 'standard',
            'issues_found' => null,
            'requires_maintenance' => false,
        ]);
    }

    public function needsRepair(): static
    {
        return $this->state(fn (array $attributes) => [
            'safety_status' => 'needs_repair',
            'requires_maintenance' => true,
            'issues_found' => [
                [
                    'issue' => 'نیاز به تعمیر',
                    'severity' => 'medium',
                    'description' => 'قفسه نیاز به تعمیر دارد',
                ],
            ],
        ]);
    }

    public function critical(): static
    {
        return $this->state(fn (array $attributes) => [
            'safety_status' => 'critical',
            'requires_maintenance' => true,
            'issues_found' => [
                [
                    'issue' => 'آسیب جدی',
                    'severity' => 'critical',
                    'description' => 'آسیب جدی در ساختار قفسه',
                ],
            ],
        ]);
    }

    public function outOfService(): static
    {
        return $this->state(fn (array $attributes) => [
            'safety_status' => 'out_of_service',
            'requires_maintenance' => true,
            'issues_found' => [
                [
                    'issue' => 'خارج از سرویس',
                    'severity' => 'critical',
                    'description' => 'قفسه خارج از سرویس است',
                ],
            ],
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'next_inspection_date' => $this->faker->dateTimeBetween('-3 months', '-1 month'),
        ]);
    }
}
