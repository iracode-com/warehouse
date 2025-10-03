<?php

namespace Tests\Feature\Location;

use App\Models\Location\Corridor;
use App\Models\Location\Zone;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CorridorTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_corridor(): void
    {
        $warehouse = Warehouse::factory()->create();
        $zone = Zone::factory()->create(['warehouse_id' => $warehouse->id]);
        
        $corridor = Corridor::factory()->create([
            'zone_id' => $zone->id,
            'code' => 'C001',
            'name' => 'راهرو اصلی',
            'access_type' => 'forklift',
            'width' => 4.5,
            'length' => 50,
        ]);

        $this->assertDatabaseHas('corridors', [
            'code' => 'C001',
            'name' => 'راهرو اصلی',
            'access_type' => 'forklift',
            'width' => 4.5,
            'length' => 50,
        ]);
    }

    public function test_corridor_belongs_to_zone(): void
    {
        $zone = Zone::factory()->create();
        $corridor = Corridor::factory()->create(['zone_id' => $zone->id]);

        $this->assertInstanceOf(Zone::class, $corridor->zone);
        $this->assertEquals($zone->id, $corridor->zone->id);
    }

    public function test_corridor_has_racks(): void
    {
        $corridor = Corridor::factory()->create();
        $racks = $corridor->racks()->createMany([
            ['code' => 'R001', 'name' => 'قفسه 1', 'corridor_id' => $corridor->id, 'rack_type' => 'fixed', 'level_count' => 4, 'capacity_per_level' => 500, 'height' => 3, 'width' => 2, 'depth' => 1, 'max_weight' => 2000],
            ['code' => 'R002', 'name' => 'قفسه 2', 'corridor_id' => $corridor->id, 'rack_type' => 'fixed', 'level_count' => 4, 'capacity_per_level' => 500, 'height' => 3, 'width' => 2, 'depth' => 1, 'max_weight' => 2000],
        ]);

        $this->assertCount(2, $corridor->racks);
    }

    public function test_corridor_access_type_label(): void
    {
        $corridor = Corridor::factory()->create(['access_type' => 'forklift']);
        
        $this->assertEquals('لیفتراک', $corridor->access_type_label);
    }

    public function test_corridor_area_calculation(): void
    {
        $corridor = Corridor::factory()->create([
            'width' => 4.0,
            'length' => 30.0,
        ]);

        $this->assertEquals(120.0, $corridor->area);
    }

    public function test_corridor_full_location(): void
    {
        $warehouse = Warehouse::factory()->create(['title' => 'انبار اصلی']);
        $zone = Zone::factory()->create(['warehouse_id' => $warehouse->id, 'name' => 'منطقه عمومی']);
        $corridor = Corridor::factory()->create([
            'zone_id' => $zone->id,
            'name' => 'راهرو اصلی',
            'code' => 'C001',
        ]);

        $expected = "انبار اصلی - منطقه عمومی - راهرو اصلی (C001)";
        $this->assertEquals($expected, $corridor->full_location);
    }
}
