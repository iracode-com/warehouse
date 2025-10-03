<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Models\CategoryAttributeValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_root_category(): void
    {
        $category = Category::create([
            'code' => 'MAIN-0001',
            'name' => 'امدادی',
            'description' => 'کالاهای امدادی',
            'hierarchy_level' => 1,
            'parent_id' => null,
            'order_index' => 1,
            'status' => 'active',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $this->assertDatabaseHas('categories', [
            'code' => 'MAIN-0001',
            'name' => 'امدادی',
            'hierarchy_level' => 1,
            'parent_id' => null,
        ]);

        $this->assertTrue($category->isRoot());
        $this->assertFalse($category->isLeaf());
    }

    public function test_can_create_child_category(): void
    {
        $parent = Category::create([
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

        $child = Category::create([
            'code' => 'SUB-0001',
            'name' => 'تجهیزات امداد',
            'hierarchy_level' => 2,
            'parent_id' => $parent->id,
            'order_index' => 1,
            'status' => 'active',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $this->assertDatabaseHas('categories', [
            'code' => 'SUB-0001',
            'parent_id' => $parent->id,
        ]);

        $this->assertTrue($parent->children->contains($child));
        $this->assertEquals($parent->id, $child->parent->id);
    }

    public function test_can_get_full_path(): void
    {
        $parent = Category::create([
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

        $child = Category::create([
            'code' => 'SUB-0001',
            'name' => 'تجهیزات امداد',
            'hierarchy_level' => 2,
            'parent_id' => $parent->id,
            'order_index' => 1,
            'status' => 'active',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $this->assertEquals('امدادی > تجهیزات امداد', $child->getFullPathAttribute());
    }

    public function test_can_get_breadcrumb(): void
    {
        $parent = Category::create([
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

        $child = Category::create([
            'code' => 'SUB-0001',
            'name' => 'تجهیزات امداد',
            'hierarchy_level' => 2,
            'parent_id' => $parent->id,
            'order_index' => 1,
            'status' => 'active',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $breadcrumb = $child->getBreadcrumbAttribute();

        $this->assertCount(2, $breadcrumb);
        $this->assertEquals('امدادی', $breadcrumb[0]['name']);
        $this->assertEquals('تجهیزات امداد', $breadcrumb[1]['name']);
    }

    public function test_can_create_category_attribute(): void
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
            'name' => 'risk_level',
            'label' => 'سطح ریسک',
            'type' => 'select',
            'options' => [
                'low' => 'کم',
                'medium' => 'متوسط',
                'high' => 'بالا',
            ],
            'is_required' => true,
            'is_searchable' => true,
            'is_filterable' => true,
            'order_index' => 1,
        ]);

        $this->assertDatabaseHas('category_attributes', [
            'category_id' => $category->id,
            'name' => 'risk_level',
            'type' => 'select',
        ]);

        $this->assertTrue($category->attributes->contains($attribute));
    }

    public function test_can_set_attribute_value(): void
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
            'name' => 'risk_level',
            'label' => 'سطح ریسک',
            'type' => 'select',
            'options' => [
                'low' => 'کم',
                'medium' => 'متوسط',
                'high' => 'بالا',
            ],
            'is_required' => true,
            'is_searchable' => true,
            'is_filterable' => true,
            'order_index' => 1,
        ]);

        $attributeValue = CategoryAttributeValue::create([
            'category_id' => $category->id,
            'attribute_id' => $attribute->id,
            'value' => 'high',
        ]);

        $this->assertDatabaseHas('category_attribute_values', [
            'category_id' => $category->id,
            'attribute_id' => $attribute->id,
            'value' => 'high',
        ]);

        $this->assertEquals('high', $attributeValue->getValue());
    }

    public function test_can_move_category(): void
    {
        $parent1 = Category::create([
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

        $parent2 = Category::create([
            'code' => 'MAIN-0002',
            'name' => 'اسقاط',
            'hierarchy_level' => 1,
            'parent_id' => null,
            'order_index' => 2,
            'status' => 'active',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $child = Category::create([
            'code' => 'SUB-0001',
            'name' => 'تجهیزات امداد',
            'hierarchy_level' => 2,
            'parent_id' => $parent1->id,
            'order_index' => 1,
            'status' => 'active',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $result = $child->moveTo($parent2);

        $this->assertTrue($result);
        $this->assertEquals($parent2->id, $child->parent_id);
        $this->assertEquals($parent2->hierarchy_level + 1, $child->hierarchy_level);
    }

    public function test_can_get_active_children(): void
    {
        $parent = Category::create([
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

        Category::create([
            'code' => 'SUB-0001',
            'name' => 'تجهیزات امداد',
            'hierarchy_level' => 2,
            'parent_id' => $parent->id,
            'order_index' => 1,
            'status' => 'active',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        Category::create([
            'code' => 'SUB-0002',
            'name' => 'تجهیزات غیرفعال',
            'hierarchy_level' => 2,
            'parent_id' => $parent->id,
            'order_index' => 2,
            'status' => 'inactive',
            'is_leaf' => false,
            'children_count' => 0,
            'items_count' => 0,
        ]);

        $activeChildren = $parent->getActiveChildren();

        $this->assertCount(1, $activeChildren->get());
        $this->assertEquals('تجهیزات امداد', $activeChildren->first()->name);
    }
}
