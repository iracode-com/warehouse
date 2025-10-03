<?php

namespace Database\Seeders;

use App\Models\Location\Corridor;
use App\Models\Location\Pallet;
use App\Models\Location\Rack;
use App\Models\Location\RackInspection;
use App\Models\Location\ShelfLevel;
use App\Models\Location\Zone;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create warehouses if they don't exist
        $warehouses = Warehouse::factory()->count(3)->create();

        foreach ($warehouses as $warehouse) {
            // Create zones for each warehouse
            $zones = collect([
                Zone::factory()->general()->create(['warehouse_id' => $warehouse->id]),
                Zone::factory()->coldStorage()->create(['warehouse_id' => $warehouse->id]),
                Zone::factory()->hazardousMaterials()->create(['warehouse_id' => $warehouse->id]),
            ]);

            foreach ($zones as $zone) {
                // Create corridors for each zone
                $corridors = Corridor::factory()
                    ->count(rand(3, 6))
                    ->create(['zone_id' => $zone->id]);

                foreach ($corridors as $corridor) {
                    // Create racks for each corridor
                    $racks = Rack::factory()
                        ->count(rand(5, 15))
                        ->create(['corridor_id' => $corridor->id]);

                    foreach ($racks as $rack) {
                        // Create shelf levels for each rack
                        $shelfLevels = ShelfLevel::factory()
                            ->count($rack->level_count)
                            ->create(['rack_id' => $rack->id]);

                        // Create pallets for some shelf levels
                        foreach ($shelfLevels->random(rand(2, 5)) as $shelfLevel) {
                            Pallet::factory()
                                ->count(rand(1, 3))
                                ->create(['shelf_level_id' => $shelfLevel->id]);
                        }

                        // Create inspections for some racks
                        if (rand(0, 1)) {
                            RackInspection::factory()
                                ->count(rand(1, 3))
                                ->create(['rack_id' => $rack->id]);
                        }
                    }
                }
            }
        }

        // Create some standalone pallets
        Pallet::factory()
            ->count(20)
            ->available()
            ->create();

        // Create some damaged pallets
        Pallet::factory()
            ->count(5)
            ->damaged()
            ->create();

        // Create some pallets in maintenance
        Pallet::factory()
            ->count(3)
            ->maintenance()
            ->create();
    }
}
