<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\CategoryAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoryAttribute>
 */
class CategoryAttributeFactory extends Factory
{
    protected $model = CategoryAttribute::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement([
            'text', 'number', 'date', 'datetime', 'boolean', 
            'select', 'multiselect', 'textarea'
        ]);

        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->unique()->slug(2),
            'label' => $this->faker->words(2, true),
            'type' => $type,
            'options' => $this->generateOptions($type),
            'default_value' => $this->generateDefaultValue($type),
            'is_required' => $this->faker->boolean(30),
            'is_searchable' => $this->faker->boolean(50),
            'is_filterable' => $this->faker->boolean(40),
            'order_index' => $this->faker->numberBetween(0, 100),
            'validation_rules' => $this->generateValidationRules($type),
            'help_text' => $this->faker->optional(0.4)->sentence(),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    /**
     * Generate options for select/multiselect fields
     */
    protected function generateOptions(string $type): ?array
    {
        if (!in_array($type, ['select', 'multiselect'])) {
            return null;
        }

        $options = [];
        $count = $this->faker->numberBetween(2, 5);
        
        for ($i = 0; $i < $count; $i++) {
            $key = $this->faker->unique()->slug();
            $value = $this->faker->words(2, true);
            $options[$key] = $value;
        }

        return $options;
    }

    /**
     * Generate default value based on type
     */
    protected function generateDefaultValue(string $type): ?string
    {
        return match ($type) {
            'text' => $this->faker->optional(0.3)->words(2, true),
            'number' => $this->faker->optional(0.3)->numberBetween(1, 100),
            'date' => $this->faker->optional(0.3)->date(),
            'datetime' => $this->faker->optional(0.3)->dateTime()->format('Y-m-d H:i:s'),
            'boolean' => $this->faker->optional(0.3)->boolean(),
            'select' => $this->faker->optional(0.3)->randomElement(['option1', 'option2', 'option3']),
            'multiselect' => $this->faker->optional(0.3)->randomElements(['option1', 'option2', 'option3'], 2),
            'textarea' => $this->faker->optional(0.3)->sentence(),
            default => null,
        };
    }

    /**
     * Generate validation rules based on type
     */
    protected function generateValidationRules(string $type): ?array
    {
        $rules = [];

        if ($type === 'number') {
            $rules['min'] = $this->faker->numberBetween(0, 10);
            $rules['max'] = $this->faker->numberBetween(100, 1000);
        }

        if ($type === 'text' || $type === 'textarea') {
            $rules['max_length'] = $this->faker->numberBetween(50, 500);
        }

        if ($type === 'date' || $type === 'datetime') {
            $rules['min_date'] = $this->faker->optional(0.3)->date();
            $rules['max_date'] = $this->faker->optional(0.3)->date();
        }

        return empty($rules) ? null : $rules;
    }

    /**
     * Create a text attribute
     */
    public function text(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'text',
            'options' => null,
        ]);
    }

    /**
     * Create a number attribute
     */
    public function number(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'number',
            'options' => null,
        ]);
    }

    /**
     * Create a date attribute
     */
    public function date(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'date',
            'options' => null,
        ]);
    }

    /**
     * Create a boolean attribute
     */
    public function boolean(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'boolean',
            'options' => null,
        ]);
    }

    /**
     * Create a select attribute
     */
    public function select(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'select',
            'options' => [
                'option1' => 'گزینه اول',
                'option2' => 'گزینه دوم',
                'option3' => 'گزینه سوم',
            ],
        ]);
    }

    /**
     * Create a required attribute
     */
    public function required(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_required' => true,
        ]);
    }

    /**
     * Create a searchable attribute
     */
    public function searchable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_searchable' => true,
        ]);
    }

    /**
     * Create a filterable attribute
     */
    public function filterable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_filterable' => true,
        ]);
    }
}
