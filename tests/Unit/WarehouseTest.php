<?php

namespace Tests\Unit;

use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_warehouse(): void
    {
        $warehouse = Warehouse::factory()->create([
            'title' => 'انبار مرکزی',
            'manager_name' => 'احمد محمدی',
            'manager_phone' => '09123456789',
            'usage_type' => 'emergency',
            'province' => 'تهران',
            'branch' => 'شعبه مرکزی',
            'base' => 'پایگاه اصلی',
            'warehouse_info' => 'اطلاعات انبار مرکزی',
            'establishment_year' => 1390,
            'construction_year' => 1390,
            'population_census' => 50000,
            'ownership_type' => 'owned',
            'area' => 1000.50,
            'structure_type' => 'concrete',
            'warehouse_count' => 5,
            'warehouse_insurance' => 'yes',
            'building_insurance' => 'yes',
            'ram_rack' => 'yes',
            'telephone' => 'yes',
            'address' => 'تهران، خیابان ولیعصر',
        ]);

        $this->assertDatabaseHas('warehouses', [
            'title' => 'انبار مرکزی',
            'manager_name' => 'احمد محمدی',
            'usage_type' => 'emergency',
            'province' => 'تهران',
        ]);
    }

    public function test_warehouse_usage_type_labels(): void
    {
        $warehouse = Warehouse::factory()->create(['usage_type' => 'emergency']);
        
        $this->assertEquals('امدادی', $warehouse->usage_type_label);
    }

    public function test_warehouse_ownership_type_labels(): void
    {
        $warehouse = Warehouse::factory()->create(['ownership_type' => 'owned']);
        
        $this->assertEquals('مالکیتی', $warehouse->ownership_type_label);
    }

    public function test_warehouse_structure_type_labels(): void
    {
        $warehouse = Warehouse::factory()->create(['structure_type' => 'concrete']);
        
        $this->assertEquals('بتنی', $warehouse->structure_type_label);
    }

    public function test_warehouse_casts(): void
    {
        $warehouse = Warehouse::factory()->create([
            'area' => 1000.50,
            'longitude' => 51.3890,
            'latitude' => 35.6892,
        ]);

        $this->assertIsFloat($warehouse->area);
        $this->assertIsFloat($warehouse->longitude);
        $this->assertIsFloat($warehouse->latitude);
    }
}
