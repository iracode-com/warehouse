<?php

namespace Tests\Feature;

use App\Models\ProductProfile;
use App\Models\Item;
use App\Models\ActivityLocation;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UltimateTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_profile_model_exists()
    {
        $this->assertTrue(class_exists(ProductProfile::class));
    }

    public function test_item_model_exists()
    {
        $this->assertTrue(class_exists(Item::class));
    }

    public function test_activity_location_model_exists()
    {
        $this->assertTrue(class_exists(ActivityLocation::class));
    }

    public function test_course_model_exists()
    {
        $this->assertTrue(class_exists(Course::class));
    }

    public function test_product_profile_has_factory()
    {
        $this->assertTrue(method_exists(ProductProfile::class, 'factory'));
    }

    public function test_item_has_factory()
    {
        $this->assertTrue(method_exists(Item::class, 'factory'));
    }

    public function test_activity_location_has_factory()
    {
        $this->assertTrue(method_exists(ActivityLocation::class, 'factory'));
    }

    public function test_course_has_factory()
    {
        $this->assertTrue(method_exists(Course::class, 'factory'));
    }

    public function test_product_profile_status_constants()
    {
        $this->assertEquals('active', ProductProfile::STATUS_ACTIVE);
        $this->assertEquals('inactive', ProductProfile::STATUS_INACTIVE);
        $this->assertEquals('discontinued', ProductProfile::STATUS_DISCONTINUED);
    }

    public function test_product_profile_status_options()
    {
        $options = ProductProfile::getStatusOptions();
        $this->assertIsArray($options);
        $this->assertArrayHasKey(ProductProfile::STATUS_ACTIVE, $options);
        $this->assertArrayHasKey(ProductProfile::STATUS_INACTIVE, $options);
        $this->assertArrayHasKey(ProductProfile::STATUS_DISCONTINUED, $options);
    }
}
