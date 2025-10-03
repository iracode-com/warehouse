<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Models\Rule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rule>
 */
class RuleFactory extends Factory
{
    protected $model = Rule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ruleType = $this->faker->randomElement(['numeric', 'date', 'string', 'boolean', 'json']);
        $conditionType = $this->getConditionTypeForRuleType($ruleType);
        $alertType = $this->faker->randomElement(['info', 'warning', 'error', 'critical']);

        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional(0.7)->sentence(),
            'category_id' => Category::factory(),
            'attribute_id' => CategoryAttribute::factory(),
            'rule_type' => $ruleType,
            'condition_type' => $conditionType,
            'condition_value' => $this->generateConditionValue($ruleType, $conditionType),
            'condition_values' => $this->generateConditionValues($conditionType),
            'alert_type' => $alertType,
            'alert_title' => $this->generateAlertTitle($alertType),
            'alert_message' => $this->generateAlertMessage($alertType),
            'alert_recipients' => $this->generateAlertRecipients(),
            'priority' => $this->faker->numberBetween(1, 10),
            'is_active' => $this->faker->boolean(80),
            'is_realtime' => $this->faker->boolean(20),
            'check_interval' => $this->faker->numberBetween(300, 3600), // 5 minutes to 1 hour
            'last_checked' => $this->faker->optional(0.7)->dateTimeBetween('-1 week', 'now'),
            'trigger_count' => $this->faker->numberBetween(0, 50),
            'metadata' => $this->generateMetadata(),
        ];
    }

    /**
     * Get condition type based on rule type
     */
    protected function getConditionTypeForRuleType(string $ruleType): string
    {
        return match ($ruleType) {
            'numeric' => $this->faker->randomElement([
                'equals', 'not_equals', 'greater_than', 'less_than', 
                'greater_equal', 'less_equal', 'between', 'not_between'
            ]),
            'date' => $this->faker->randomElement([
                'equals', 'not_equals', 'greater_than', 'less_than', 
                'greater_equal', 'less_equal', 'between', 'not_between'
            ]),
            'string' => $this->faker->randomElement([
                'equals', 'not_equals', 'contains', 'not_contains', 
                'in', 'not_in', 'is_null', 'is_not_null'
            ]),
            'boolean' => $this->faker->randomElement(['equals', 'not_equals']),
            'json' => $this->faker->randomElement(['in', 'not_in', 'is_null', 'is_not_null']),
            default => 'equals',
        };
    }

    /**
     * Generate condition value based on rule type
     */
    protected function generateConditionValue(string $ruleType, string $conditionType): ?string
    {
        if (in_array($conditionType, ['in', 'not_in', 'between', 'not_between', 'is_null', 'is_not_null'])) {
            return null;
        }

        return match ($ruleType) {
            'numeric' => $this->faker->numberBetween(1, 100),
            'date' => $this->faker->date(),
            'string' => $this->faker->word(),
            'boolean' => $this->faker->boolean(),
            'json' => $this->faker->word(),
            default => $this->faker->word(),
        };
    }

    /**
     * Generate condition values for array conditions
     */
    protected function generateConditionValues(string $conditionType): ?array
    {
        if (!in_array($conditionType, ['in', 'not_in', 'between', 'not_between'])) {
            return null;
        }

        if (in_array($conditionType, ['between', 'not_between'])) {
            return [
                $this->faker->numberBetween(1, 50),
                $this->faker->numberBetween(51, 100),
            ];
        }

        return $this->faker->randomElements([
            'option1', 'option2', 'option3', 'option4', 'option5'
        ], $this->faker->numberBetween(2, 4));
    }

    /**
     * Generate alert title based on alert type
     */
    protected function generateAlertTitle(string $alertType): string
    {
        $titles = [
            'info' => [
                'اطلاعات جدید در دسته‌بندی',
                'به‌روزرسانی موجودی',
                'تغییر وضعیت کالا',
            ],
            'warning' => [
                'هشدار موجودی کم',
                'تاریخ انقضای نزدیک',
                'وضعیت غیرعادی کالا',
            ],
            'error' => [
                'خطا در پردازش کالا',
                'مشکل در ذخیره‌سازی',
                'خطای سیستم',
            ],
            'critical' => [
                'بحران در انبار',
                'کالای بحرانی منقضی شده',
                'وضعیت اضطراری',
            ],
        ];

        return $this->faker->randomElement($titles[$alertType]);
    }

    /**
     * Generate alert message
     */
    protected function generateAlertMessage(string $alertType): string
    {
        $messages = [
            'info' => 'اطلاعات جدیدی در دسته‌بندی {category_name} موجود است.',
            'warning' => 'هشدار: موجودی کالای {category_name} کمتر از حد مجاز است.',
            'error' => 'خطا در پردازش کالای {category_name} رخ داده است.',
            'critical' => 'بحران: کالای {category_name} نیاز به توجه فوری دارد.',
        ];

        return $messages[$alertType];
    }

    /**
     * Generate alert recipients
     */
    protected function generateAlertRecipients(): ?array
    {
        return $this->faker->optional(0.6)->randomElements([
            'admin@example.com',
            'manager@example.com',
            'warehouse@example.com',
            'supervisor@example.com',
        ], $this->faker->numberBetween(1, 3));
    }

    /**
     * Generate metadata
     */
    protected function generateMetadata(): ?array
    {
        return $this->faker->optional(0.4)->randomElements([
            'check_leaf' => $this->faker->boolean(),
            'max_children' => $this->faker->numberBetween(5, 20),
            'check_inactive' => $this->faker->boolean(),
            'auto_resolve' => $this->faker->boolean(),
            'escalation_time' => $this->faker->numberBetween(1, 24),
        ], $this->faker->numberBetween(1, 3));
    }

    /**
     * Create a numeric rule
     */
    public function numeric(): static
    {
        return $this->state(fn (array $attributes) => [
            'rule_type' => 'numeric',
            'condition_type' => $this->faker->randomElement([
                'greater_than', 'less_than', 'greater_equal', 'less_equal'
            ]),
            'condition_value' => $this->faker->numberBetween(1, 100),
        ]);
    }

    /**
     * Create a date rule
     */
    public function date(): static
    {
        return $this->state(fn (array $attributes) => [
            'rule_type' => 'date',
            'condition_type' => $this->faker->randomElement([
                'less_than', 'greater_than', 'equals'
            ]),
            'condition_value' => $this->faker->date(),
        ]);
    }

    /**
     * Create a string rule
     */
    public function string(): static
    {
        return $this->state(fn (array $attributes) => [
            'rule_type' => 'string',
            'condition_type' => $this->faker->randomElement([
                'equals', 'contains', 'not_contains'
            ]),
            'condition_value' => $this->faker->word(),
        ]);
    }

    /**
     * Create a boolean rule
     */
    public function boolean(): static
    {
        return $this->state(fn (array $attributes) => [
            'rule_type' => 'boolean',
            'condition_type' => 'equals',
            'condition_value' => $this->faker->boolean(),
        ]);
    }

    /**
     * Create a high priority rule
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => $this->faker->numberBetween(7, 10),
            'alert_type' => $this->faker->randomElement(['error', 'critical']),
        ]);
    }

    /**
     * Create a realtime rule
     */
    public function realtime(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_realtime' => true,
            'check_interval' => 0,
        ]);
    }

    /**
     * Create an active rule
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Create an inactive rule
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
