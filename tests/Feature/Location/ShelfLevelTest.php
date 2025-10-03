<?php

namespace Tests\Feature\Location;

use App\Models\Location\Corridor;
use App\Models\Location\Rack;
use App\Models\Location\ShelfLevel;
use App\Models\Location\Zone;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShelfLevelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_shelf_level(): void
    {
        $warehouse = Warehouse::factory()->create();
        $zone = Zone::factory()->create(['warehouse_id' => $warehouse->id]);
        $corridor = Corridor::factory()->create(['zone_id' => $zone->id]);
        $rack = Rack::factory()->create(['corridor_id' => $corridor->id]);
        
        $shelfLevel = ShelfLevel::factory()->create([
            'rack_id' => $rack->id,
            'code' => 'L001',
            'name' => 'طبقه اول',
            'level_number' => 1,
            'max_weight' => 500,
            'allowed_product_type' => 'general',
            'occupancy_status' => 'empty',
            'current_weight' => 0,
            'height' => 0.5,
            'width' => 2,
            'depth' => 1,
        ]);

        $this->assertDatabaseHas('shelf_levels', [
            'code' => 'L001',
            'name' => 'طبقه اول',
            'level_number' => 1,
            'max_weight' => 500,
            'allowed_product_type' => 'general',
            'occupancy_status' => 'empty',
        ]);
    }

    public function test_shelf_level_belongs_to_rack(): void
    {
        $rack = Rack::factory()->create();
        $shelfLevel = ShelfLevel::factory()->create(['rack_id' => $rack->id]);

        $this->assertInstanceOf(Rack::class, $shelfLevel->rack);
        $this->assertEquals($rack->id, $shelfLevel->rack->id);
    }

    public function test_shelf_level_has_pallets(): void
    {
        $shelfLevel = ShelfLevel::factory()->create();
        $pallets = $shelfLevel->pallets()->createMany([
            ['code' => 'P001', 'name' => 'پالت 1', 'shelf_level_id' => $shelfLevel->id, 'pallet_type' => 'standard', 'length' => 1.2, 'width' => 0.8, 'height' => 0.144, 'max_weight' => 1000, 'current_weight' => 500],
            ['code' => 'P002', 'name' => 'پالت 2', 'shelf_level_id' => $shelfLevel->id, 'pallet_type' => 'standard', 'length' => 1.2, 'width' => 0.8, 'height' => 0.144, 'max_weight' => 1000, 'current_weight' => 300],
        ]);

        $this->assertCount(2, $shelfLevel->pallets);
    }

    public function test_shelf_level_allowed_product_type_label(): void
    {
        $shelfLevel = ShelfLevel::factory()->create(['allowed_product_type' => 'hazardous']);
        
        $this->assertEquals('مواد خطرناک', $shelfLevel->allowed_product_type_label);
    }

    public function test_shelf_level_occupancy_status_label(): void
    {
        $shelfLevel = ShelfLevel::factory()->create(['occupancy_status' => 'partial']);
        
        $this->assertEquals('نیمه‌پر', $shelfLevel->occupancy_status_label);
    }

    public function test_shelf_level_volume_calculation(): void
    {
        $shelfLevel = ShelfLevel::factory()->create([
            'height' => 0.5,
            'width' => 2.0,
            'depth' => 1.0,
        ]);

        $this->assertEquals(1.0, $shelfLevel->volume);
    }

    public function test_shelf_level_available_weight_calculation(): void
    {
        $shelfLevel = ShelfLevel::factory()->create([
            'max_weight' => 1000,
            'current_weight' => 300,
        ]);

        $this->assertEquals(700.0, $shelfLevel->available_weight);
    }

    public function test_shelf_level_occupancy_percentage_calculation(): void
    {
        $shelfLevel = ShelfLevel::factory()->create([
            'max_weight' => 1000,
            'current_weight' => 300,
        ]);

        $this->assertEquals(30.0, $shelfLevel->occupancy_percentage);
    }

    public function test_shelf_level_update_occupancy_status(): void
    {
        $shelfLevel = ShelfLevel::factory()->create([
            'max_weight' => 1000,
            'current_weight' => 0,
            'occupancy_status' => 'full',
        ]);

        $shelfLevel->updateOccupancyStatus();

        $this->assertEquals('empty', $shelfLevel->fresh()->occupancy_status);
    }

    public function test_shelf_level_full_location(): void
    {
        $warehouse = Warehouse::factory()->create(['title' => 'انبار اصلی']);
        $zone = Zone::factory()->create(['warehouse_id' => $warehouse->id, 'name' => 'منطقه عمومی']);
        $corridor = Corridor::factory()->create(['zone_id' => $zone->id, 'name' => 'راهرو اصلی']);
        $rack = Rack::factory()->create(['corridor_id' => $corridor->id, 'name' => 'قفسه اصلی']);
        $shelfLevel = ShelfLevel::factory()->create([
            'rack_id' => $rack->id,
            'name' => 'طبقه اول',
            'code' => 'L001',
        ]);

        $expected = "انبار اصلی - منطقه عمومی - راهرو اصلی - قفسه اصلی - طبقه اول (L001)";
        $this->assertEquals($expected, $shelfLevel->full_location);
    }
}
