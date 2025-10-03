<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional()->paragraph(),
            'duration_hours' => $this->faker->numberBetween(10, 100),
            'instructor' => $this->faker->optional()->name(),
            'institution' => $this->faker->optional()->company(),
            'completion_date' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'certificate_number' => $this->faker->optional()->bothify('CERT-####-####'),
            'status' => $this->faker->boolean(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'completion_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'certificate_number' => $this->faker->bothify('CERT-####-####'),
        ]);
    }
}
