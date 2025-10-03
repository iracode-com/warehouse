<?php

namespace Tests\Feature\Location;

use App\Models\Location\Corridor;
use App\Models\Location\Pallet;
use App\Models\Location\Rack;
use App\Models\Location\ShelfLevel;
use App\Models\Location\Zone;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PalletTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_pallet(): void
    {
        $warehouse = Warehouse::factory()->create();
        $zone = Zone::factory()->create(['warehouse_id' => $warehouse->id]);
        $corridor = Corridor::factory()->create(['zone_id' => $zone->id]);
        $rack = Rack::factory()->create(['corridor_id' => $corridor->id]);
        $shelfLevel = ShelfLevel::factory()->create(['rack_id' => $rack->id]);
        
        $pallet = Pallet::factory()->create([
            'shelf_level_id' => $shelfLevel->id,
            'code' => 'P001',
            'name' => 'پالت اصلی',
            'pallet_type' => 'standard',
            'length' => 1.2,
            'width' => 0.8,
            'height' => 0.144,
            'max_weight' => 1000,
            'current_weight' => 500,
            'status' => 'occupied',
        ]);

        $this->assertDatabaseHas('pallets', [
            'code' => 'P001',
            'name' => 'پالت اصلی',
            'pallet_type' => 'standard',
            'status' => 'occupied',
        ]);
    }

    public function test_pallet_belongs_to_shelf_level(): void
    {
        $shelfLevel = ShelfLevel::factory()->create();
        $pallet = Pallet::factory()->create(['shelf_level_id' => $shelfLevel->id]);

        $this->assertInstanceOf(ShelfLevel::class, $pallet->shelfLevel);
        $this->assertEquals($shelfLevel->id, $pallet->shelfLevel->id);
    }

    public function test_pallet_type_label(): void
    {
        $pallet = Pallet::factory()->create(['pallet_type' => 'euro_pallet']);
        
        $this->assertEquals('پالت اروپایی', $pallet->pallet_type_label);
    }

    public function test_pallet_status_label(): void
    {
        $pallet = Pallet::factory()->create(['status' => 'maintenance']);
        
        $this->assertEquals('تعمیر', $pallet->status_label);
    }

    public function test_pallet_volume_calculation(): void
    {
        $pallet = Pallet::factory()->create([
            'length' => 1.2,
            'width' => 0.8,
            'height' => 0.144,
        ]);

        $this->assertEquals(0.13824, $pallet->volume);
    }

    public function test_pallet_available_weight_calculation(): void
    {
        $pallet = Pallet::factory()->create([
            'max_weight' => 1000,
            'current_weight' => 300,
        ]);

        $this->assertEquals(700.0, $pallet->available_weight);
    }

    public function test_pallet_occupancy_percentage_calculation(): void
    {
        $pallet = Pallet::factory()->create([
            'max_weight' => 1000,
            'current_weight' => 300,
        ]);

        $this->assertEquals(30.0, $pallet->occupancy_percentage);
    }

    public function test_pallet_update_current_position(): void
    {
        $warehouse = Warehouse::factory()->create();
        $zone = Zone::factory()->create(['warehouse_id' => $warehouse->id, 'code' => 'Z001']);
        $corridor = Corridor::factory()->create(['zone_id' => $zone->id, 'code' => 'C001']);
        $rack = Rack::factory()->create(['corridor_id' => $corridor->id, 'code' => 'R001']);
        $shelfLevel = ShelfLevel::factory()->create(['rack_id' => $rack->id, 'code' => 'L001']);
        
        $pallet = Pallet::factory()->create(['shelf_level_id' => $shelfLevel->id]);
        $pallet->updateCurrentPosition();

        $this->assertEquals('C001-R001-L001', $pallet->fresh()->current_position);
    }

    public function test_pallet_move_to_shelf_level(): void
    {
        $warehouse = Warehouse::factory()->create();
        $zone = Zone::factory()->create(['warehouse_id' => $warehouse->id]);
        $corridor = Corridor::factory()->create(['zone_id' => $zone->id]);
        $rack = Rack::factory()->create(['corridor_id' => $corridor->id]);
        
        $shelfLevel1 = ShelfLevel::factory()->create(['rack_id' => $rack->id, 'max_weight' => 1000, 'current_weight' => 0]);
        $shelfLevel2 = ShelfLevel::factory()->create(['rack_id' => $rack->id, 'max_weight' => 500, 'current_weight' => 0]);
        
        $pallet = Pallet::factory()->create([
            'shelf_level_id' => $shelfLevel1->id,
            'current_weight' => 300,
        ]);

        // Should succeed - shelf level has enough capacity
        $result = $pallet->moveToShelfLevel($shelfLevel2);
        $this->assertTrue($result);
        $this->assertEquals($shelfLevel2->id, $pallet->fresh()->shelf_level_id);

        // Should fail - shelf level doesn't have enough capacity
        $shelfLevel3 = ShelfLevel::factory()->create(['rack_id' => $rack->id, 'max_weight' => 200, 'current_weight' => 0]);
        $result = $pallet->moveToShelfLevel($shelfLevel3);
        $this->assertFalse($result);
    }

    public function test_pallet_full_location(): void
    {
        $warehouse = Warehouse::factory()->create(['title' => 'انبار اصلی']);
        $zone = Zone::factory()->create(['warehouse_id' => $warehouse->id, 'name' => 'منطقه عمومی']);
        $corridor = Corridor::factory()->create(['zone_id' => $zone->id, 'name' => 'راهرو اصلی']);
        $rack = Rack::factory()->create(['corridor_id' => $corridor->id, 'name' => 'قفسه اصلی']);
        $shelfLevel = ShelfLevel::factory()->create(['rack_id' => $rack->id, 'name' => 'طبقه اول']);
        $pallet = Pallet::factory()->create([
            'shelf_level_id' => $shelfLevel->id,
            'name' => 'پالت اصلی',
            'code' => 'P001',
        ]);

        $expected = "انبار اصلی - منطقه عمومی - راهرو اصلی - قفسه اصلی - طبقه اول (L001)";
        $this->assertEquals($expected, $pallet->full_location);
    }
}
