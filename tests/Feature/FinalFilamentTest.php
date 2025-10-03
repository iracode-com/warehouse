<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ProductProfile;
use App\Models\Item;
use App\Models\ActivityLocation;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinalFilamentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_product_profile_resource_exists()
    {
        $this->assertTrue(class_exists(\App\Filament\Resources\ProductProfileResource::class));
    }

    public function test_item_resource_exists()
    {
        $this->assertTrue(class_exists(\App\Filament\Resources\ItemResource::class));
    }

    public function test_activity_location_resource_exists()
    {
        $this->assertTrue(class_exists(\App\Filament\Resources\ActivityLocationResource::class));
    }

    public function test_course_resource_exists()
    {
        $this->assertTrue(class_exists(\App\Filament\Resources\CourseResource::class));
    }

    public function test_product_profile_resource_has_model()
    {
        $resource = new \App\Filament\Resources\ProductProfileResource();
        $this->assertEquals(ProductProfile::class, $resource->getModel());
    }

    public function test_item_resource_has_model()
    {
        $resource = new \App\Filament\Resources\ItemResource();
        $this->assertEquals(Item::class, $resource->getModel());
    }

    public function test_activity_location_resource_has_model()
    {
        $resource = new \App\Filament\Resources\ActivityLocationResource();
        $this->assertEquals(ActivityLocation::class, $resource->getModel());
    }

    public function test_course_resource_has_model()
    {
        $resource = new \App\Filament\Resources\CourseResource();
        $this->assertEquals(Course::class, $resource->getModel());
    }

    public function test_product_profile_resource_has_navigation()
    {
        $resource = new \App\Filament\Resources\ProductProfileResource();
        $this->assertNotNull($resource->getNavigationGroup());
        $this->assertNotNull($resource->getNavigationLabel());
    }

    public function test_item_resource_has_navigation()
    {
        $resource = new \App\Filament\Resources\ItemResource();
        $this->assertNotNull($resource->getNavigationGroup());
        $this->assertNotNull($resource->getNavigationLabel());
    }

    public function test_activity_location_resource_has_navigation()
    {
        $resource = new \App\Filament\Resources\ActivityLocationResource();
        $this->assertNotNull($resource->getNavigationGroup());
        $this->assertNotNull($resource->getNavigationLabel());
    }

    public function test_course_resource_has_navigation()
    {
        $resource = new \App\Filament\Resources\CourseResource();
        $this->assertNotNull($resource->getNavigationGroup());
        $this->assertNotNull($resource->getNavigationLabel());
    }
}
