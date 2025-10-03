<?php

namespace Tests\Feature\Location;

use App\Models\Location\Zone;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_zone(): void
    {
        $warehouse = Warehouse::factory()->create();
        
        $zone = Zone::factory()->create([
            'warehouse_id' => $warehouse->id,
            'code' => 'Z001',
            'name' => 'منطقه عمومی',
            'zone_type' => 'general',
        ]);

        $this->assertDatabaseHas('zones', [
            'code' => 'Z001',
            'name' => 'منطقه عمومی',
            'zone_type' => 'general',
            'warehouse_id' => $warehouse->id,
        ]);
    }

    public function test_zone_belongs_to_warehouse(): void
    {
        $warehouse = Warehouse::factory()->create();
        $zone = Zone::factory()->create(['warehouse_id' => $warehouse->id]);

        $this->assertInstanceOf(Warehouse::class, $zone->warehouse);
        $this->assertEquals($warehouse->id, $zone->warehouse->id);
    }

    public function test_zone_has_corridors(): void
    {
        $zone = Zone::factory()->create();
        $corridors = $zone->corridors()->createMany([
            ['code' => 'C001', 'name' => 'راهرو 1', 'zone_id' => $zone->id, 'width' => 3, 'length' => 20],
            ['code' => 'C002', 'name' => 'راهرو 2', 'zone_id' => $zone->id, 'width' => 3, 'length' => 20],
        ]);

        $this->assertCount(2, $zone->corridors);
    }

    public function test_zone_type_label(): void
    {
        $zone = Zone::factory()->create(['zone_type' => 'cold_storage']);
        
        $this->assertEquals('ذخیره‌سازی سرد', $zone->zone_type_label);
    }

    public function test_zone_full_location(): void
    {
        $warehouse = Warehouse::factory()->create(['title' => 'انبار اصلی']);
        $zone = Zone::factory()->create([
            'warehouse_id' => $warehouse->id,
            'name' => 'منطقه عمومی',
            'code' => 'Z001',
        ]);

        $expected = "انبار اصلی - منطقه عمومی (Z001)";
        $this->assertEquals($expected, $zone->full_location);
    }
}
