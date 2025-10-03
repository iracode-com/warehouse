<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Models\CategoryAttributeValue;
use App\Models\Rule;
use App\Models\Alert;
use App\Services\RuleEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RuleEngineTest extends TestCase
{
    use RefreshDatabase;

    protected RuleEngine $ruleEngine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ruleEngine = new RuleEngine();
    }

    public function test_can_process_rules(): void
    {
        // Create a category
        $category = Category::create([
            'code' => 'MAIN-0001',
            'name' => 'امدادی',
            'hierarchy_level' => 1,
            'parent_id' => null,
            'order_index' => 1,
            'status' => 'active',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        // Create a rule
        $rule = Rule::create([
            'name' => 'موجودی کم',
            'description' => 'هشدار زمانی که موجودی کالا کمتر از حد مجاز باشد',
            'category_id' => $category->id,
            'attribute_id' => null,
            'rule_type' => 'numeric',
            'condition_type' => 'less_than',
            'condition_value' => '10',
            'alert_type' => 'warning',
            'alert_title' => 'هشدار موجودی کم',
            'alert_message' => 'موجودی کالای {category_name} کمتر از حد مجاز است.',
            'priority' => 5,
            'is_active' => true,
            'is_realtime' => false,
            'check_interval' => 3600,
        ]);

        $processedCount = $this->ruleEngine->processRules();

        $this->assertGreaterThan(0, $processedCount);
    }

    public function test_can_evaluate_numeric_condition(): void
    {
        $category = Category::create([
            'code' => 'MAIN-0001',
            'name' => 'امدادی',
            'hierarchy_level' => 1,
            'parent_id' => null,
            'order_index' => 1,
            'status' => 'active',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $attribute = CategoryAttribute::create([
            'category_id' => $category->id,
            'name' => 'stock_count',
            'label' => 'تعداد موجودی',
            'type' => 'number',
            'is_required' => true,
            'is_searchable' => true,
            'is_filterable' => true,
            'order_index' => 1,
        ]);

        $attributeValue = CategoryAttributeValue::create([
            'category_id' => $category->id,
            'attribute_id' => $attribute->id,
            'numeric_value' => 5, // Low stock
        ]);

        $rule = Rule::create([
            'name' => 'موجودی کم',
            'category_id' => $category->id,
            'attribute_id' => $attribute->id,
            'rule_type' => 'numeric',
            'condition_type' => 'less_than',
            'condition_value' => '10',
            'alert_type' => 'warning',
            'alert_title' => 'هشدار موجودی کم',
            'alert_message' => 'موجودی کالای {category_name} کمتر از حد مجاز است.',
            'priority' => 5,
            'is_active' => true,
            'is_realtime' => false,
            'check_interval' => 3600,
        ]);

        $result = $this->ruleEngine->processRule($rule);

        $this->assertTrue($result);
        $this->assertDatabaseHas('alerts', [
            'rule_id' => $rule->id,
            'category_id' => $category->id,
            'title' => 'هشدار موجودی کم',
        ]);
    }

    public function test_can_evaluate_date_condition(): void
    {
        $category = Category::create([
            'code' => 'MAIN-0001',
            'name' => 'امدادی',
            'hierarchy_level' => 1,
            'parent_id' => null,
            'order_index' => 1,
            'status' => 'active',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $attribute = CategoryAttribute::create([
            'category_id' => $category->id,
            'name' => 'expiration_date',
            'label' => 'تاریخ انقضا',
            'type' => 'date',
            'is_required' => true,
            'is_searchable' => true,
            'is_filterable' => true,
            'order_index' => 1,
        ]);

        $attributeValue = CategoryAttributeValue::create([
            'category_id' => $category->id,
            'attribute_id' => $attribute->id,
            'date_value' => now()->addDays(15)->format('Y-m-d'), // Expires soon
        ]);

        $rule = Rule::create([
            'name' => 'تاریخ انقضای نزدیک',
            'category_id' => $category->id,
            'attribute_id' => $attribute->id,
            'rule_type' => 'date',
            'condition_type' => 'less_than',
            'condition_value' => now()->addDays(30)->format('Y-m-d'),
            'alert_type' => 'error',
            'alert_title' => 'تاریخ انقضای نزدیک',
            'alert_message' => 'کالای {category_name} تا 30 روز آینده منقضی می‌شود.',
            'priority' => 7,
            'is_active' => true,
            'is_realtime' => true,
            'check_interval' => 0,
        ]);

        $result = $this->ruleEngine->processRule($rule);

        $this->assertTrue($result);
        $this->assertDatabaseHas('alerts', [
            'rule_id' => $rule->id,
            'category_id' => $category->id,
            'title' => 'تاریخ انقضای نزدیک',
        ]);
    }

    public function test_can_evaluate_string_condition(): void
    {
        $category = Category::create([
            'code' => 'MAIN-0001',
            'name' => 'امدادی',
            'hierarchy_level' => 1,
            'parent_id' => null,
            'order_index' => 1,
            'status' => 'active',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $attribute = CategoryAttribute::create([
            'category_id' => $category->id,
            'name' => 'status',
            'label' => 'وضعیت',
            'type' => 'select',
            'options' => [
                'active' => 'فعال',
                'inactive' => 'غیرفعال',
                'defective' => 'معیوب',
            ],
            'is_required' => true,
            'is_searchable' => true,
            'is_filterable' => true,
            'order_index' => 1,
        ]);

        $attributeValue = CategoryAttributeValue::create([
            'category_id' => $category->id,
            'attribute_id' => $attribute->id,
            'value' => 'defective',
        ]);

        $rule = Rule::create([
            'name' => 'کالای معیوب',
            'category_id' => $category->id,
            'attribute_id' => $attribute->id,
            'rule_type' => 'string',
            'condition_type' => 'equals',
            'condition_value' => 'defective',
            'alert_type' => 'error',
            'alert_title' => 'کالای معیوب',
            'alert_message' => 'کالای {category_name} در وضعیت معیوب قرار دارد.',
            'priority' => 8,
            'is_active' => true,
            'is_realtime' => true,
            'check_interval' => 0,
        ]);

        $result = $this->ruleEngine->processRule($rule);

        $this->assertTrue($result);
        $this->assertDatabaseHas('alerts', [
            'rule_id' => $rule->id,
            'category_id' => $category->id,
            'title' => 'کالای معیوب',
        ]);
    }

    public function test_can_get_statistics(): void
    {
        // Create some test data
        Category::factory()->count(5)->create();
        Rule::factory()->count(3)->create();
        Alert::factory()->count(10)->create();

        $stats = $this->ruleEngine->getStatistics();

        $this->assertArrayHasKey('total_rules', $stats);
        $this->assertArrayHasKey('active_rules', $stats);
        $this->assertArrayHasKey('total_alerts', $stats);
        $this->assertArrayHasKey('active_alerts', $stats);
        $this->assertEquals(3, $stats['total_rules']);
        $this->assertEquals(10, $stats['total_alerts']);
    }

    public function test_can_cleanup_old_alerts(): void
    {
        // Create old alerts
        Alert::factory()->count(5)->create([
            'created_at' => now()->subDays(35),
            'status' => 'resolved',
        ]);

        // Create recent alerts
        Alert::factory()->count(3)->create([
            'created_at' => now()->subDays(10),
            'status' => 'resolved',
        ]);

        $deletedCount = $this->ruleEngine->cleanupAlerts(30);

        $this->assertEquals(5, $deletedCount);
        $this->assertDatabaseCount('alerts', 3);
    }

    public function test_can_process_rules_for_specific_category(): void
    {
        $category = Category::create([
            'code' => 'MAIN-0001',
            'name' => 'امدادی',
            'hierarchy_level' => 1,
            'parent_id' => null,
            'order_index' => 1,
            'status' => 'active',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $rule = Rule::create([
            'name' => 'قانون مخصوص',
            'category_id' => $category->id,
            'attribute_id' => null,
            'rule_type' => 'custom',
            'condition_type' => 'equals',
            'condition_value' => 'test',
            'alert_type' => 'info',
            'alert_title' => 'اطلاعات',
            'alert_message' => 'پیام تست',
            'priority' => 1,
            'is_active' => true,
            'is_realtime' => false,
            'check_interval' => 3600,
        ]);

        $processedCount = $this->ruleEngine->processRulesForCategory($category);

        $this->assertGreaterThan(0, $processedCount);
    }
}
