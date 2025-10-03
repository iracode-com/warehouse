<?php

namespace Tests\Feature\Location;

use App\Models\Location\Corridor;
use App\Models\Location\Rack;
use App\Models\Location\Zone;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RackTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_rack(): void
    {
        $warehouse = Warehouse::factory()->create();
        $zone = Zone::factory()->create(['warehouse_id' => $warehouse->id]);
        $corridor = Corridor::factory()->create(['zone_id' => $zone->id]);
        
        $rack = Rack::factory()->create([
            'corridor_id' => $corridor->id,
            'code' => 'R001',
            'name' => 'قفسه اصلی',
            'rack_type' => 'fixed',
            'level_count' => 4,
            'capacity_per_level' => 500,
            'height' => 3,
            'width' => 2,
            'depth' => 1,
            'max_weight' => 2000,
        ]);

        $this->assertDatabaseHas('racks', [
            'code' => 'R001',
            'name' => 'قفسه اصلی',
            'rack_type' => 'fixed',
            'level_count' => 4,
            'capacity_per_level' => 500,
        ]);
    }

    public function test_rack_belongs_to_corridor(): void
    {
        $corridor = Corridor::factory()->create();
        $rack = Rack::factory()->create(['corridor_id' => $corridor->id]);

        $this->assertInstanceOf(Corridor::class, $rack->corridor);
        $this->assertEquals($corridor->id, $rack->corridor->id);
    }

    public function test_rack_has_shelf_levels(): void
    {
        $rack = Rack::factory()->create(['level_count' => 4]);
        $shelfLevels = $rack->shelfLevels()->createMany([
            ['code' => 'L001', 'name' => 'طبقه 1', 'rack_id' => $rack->id, 'level_number' => 1, 'max_weight' => 500, 'allowed_product_type' => 'general', 'height' => 0.5, 'width' => 2, 'depth' => 1],
            ['code' => 'L002', 'name' => 'طبقه 2', 'rack_id' => $rack->id, 'level_number' => 2, 'max_weight' => 500, 'allowed_product_type' => 'general', 'height' => 0.5, 'width' => 2, 'depth' => 1],
        ]);

        $this->assertCount(2, $rack->shelfLevels);
    }

    public function test_rack_type_label(): void
    {
        $rack = Rack::factory()->create(['rack_type' => 'pallet_rack']);
        
        $this->assertEquals('پالت‌دار', $rack->rack_type_label);
    }

    public function test_rack_volume_calculation(): void
    {
        $rack = Rack::factory()->create([
            'height' => 3.0,
            'width' => 2.0,
            'depth' => 1.0,
        ]);

        $this->assertEquals(6.0, $rack->volume);
    }

    public function test_rack_total_capacity_calculation(): void
    {
        $rack = Rack::factory()->create([
            'level_count' => 4,
            'capacity_per_level' => 500,
        ]);

        $this->assertEquals(2000.0, $rack->total_capacity);
    }

    public function test_rack_full_location(): void
    {
        $warehouse = Warehouse::factory()->create(['title' => 'انبار اصلی']);
        $zone = Zone::factory()->create(['warehouse_id' => $warehouse->id, 'name' => 'منطقه عمومی']);
        $corridor = Corridor::factory()->create(['zone_id' => $zone->id, 'name' => 'راهرو اصلی']);
        $rack = Rack::factory()->create([
            'corridor_id' => $corridor->id,
            'name' => 'قفسه اصلی',
            'code' => 'R001',
        ]);

        $expected = "انبار اصلی - منطقه عمومی - راهرو اصلی - قفسه اصلی (R001)";
        $this->assertEquals($expected, $rack->full_location);
    }
}
