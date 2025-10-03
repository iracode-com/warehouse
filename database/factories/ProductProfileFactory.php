<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\ProductProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductProfile>
 */
class ProductProfileFactory extends Factory
{
    protected $model = ProductProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'category_id' => Category::factory(),
            'brand' => $this->faker->company(),
            'model' => $this->faker->bothify('Model-####'),
            'barcode' => $this->faker->unique()->ean13(),
            'qr_code' => $this->faker->unique()->uuid(),
            'weight' => $this->faker->randomFloat(2, 0.1, 100),
            'length' => $this->faker->randomFloat(2, 1, 100),
            'width' => $this->faker->randomFloat(2, 1, 100),
            'height' => $this->faker->randomFloat(2, 1, 100),
            'volume' => $this->faker->randomFloat(2, 0.1, 50),
            'unit_of_measure' => $this->faker->randomElement(['piece', 'kg', 'liter']),
            'primary_unit' => $this->faker->randomElement(['عدد', 'کیلوگرم', 'لیتر']),
            'secondary_unit' => $this->faker->randomElement(['بسته', 'کارتن', 'پالت']),
            'manufacturer' => $this->faker->company(),
            'country_of_origin' => $this->faker->country(),
            'shelf_life_days' => $this->faker->numberBetween(30, 365),
            'standard_cost' => $this->faker->randomFloat(2, 10, 1000),
            'pricing_method' => $this->faker->randomElement(['FIFO', 'LIFO', 'FEFO']),
            'feature_1' => $this->faker->word(),
            'feature_2' => $this->faker->word(),
            'has_expiry_date' => $this->faker->boolean(),
            'consumption_status' => $this->faker->randomElement(['high_consumption', 'strategic', 'low_consumption', 'stagnant']),
            'is_flammable' => $this->faker->boolean(),
            'has_return_policy' => $this->faker->boolean(),
            'product_address' => $this->faker->address(),
            'minimum_stock_by_location' => json_encode([
                'location1' => $this->faker->numberBetween(10, 100),
                'location2' => $this->faker->numberBetween(10, 100),
            ]),
            'reorder_point_by_location' => json_encode([
                'location1' => $this->faker->numberBetween(5, 50),
                'location2' => $this->faker->numberBetween(5, 50),
            ]),
            'has_technical_specs' => $this->faker->boolean(),
            'technical_specs' => $this->faker->optional()->paragraph(),
            'has_storage_conditions' => $this->faker->boolean(),
            'storage_conditions' => $this->faker->optional()->paragraph(),
            'has_inspection' => $this->faker->boolean(),
            'inspection_details' => $this->faker->optional()->paragraph(),
            'has_similar_products' => $this->faker->boolean(),
            'similar_products' => json_encode($this->faker->words(3)),
            'estimated_value' => $this->faker->randomFloat(2, 100, 10000),
            'annual_inflation_rate' => $this->faker->randomFloat(2, 0, 50),
            'related_warehouses' => json_encode($this->faker->words(2)),
            'status' => $this->faker->randomElement([ProductProfile::STATUS_ACTIVE, ProductProfile::STATUS_INACTIVE, ProductProfile::STATUS_DISCONTINUED]),
            'custom_attributes' => json_encode([
                'color' => $this->faker->colorName(),
                'material' => $this->faker->word(),
            ]),
            'images' => json_encode([
                $this->faker->imageUrl(),
                $this->faker->imageUrl(),
            ]),
            'documents' => json_encode([
                $this->faker->filePath(),
                $this->faker->filePath(),
            ]),
            'notes' => $this->faker->optional()->paragraph(),
            'metadata' => json_encode([
                'created_by' => $this->faker->name(),
                'last_updated' => $this->faker->dateTime(),
            ]),
            'additional_description' => $this->faker->optional()->paragraph(),
            'is_active' => $this->faker->boolean(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProductProfile::STATUS_ACTIVE,
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProductProfile::STATUS_INACTIVE,
            'is_active' => false,
        ]);
    }
}
