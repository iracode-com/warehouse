<?php

namespace Tests\Feature;

use App\Models\Warehouse;
use App\Models\WarehouseManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_warehouse_manager(): void
    {
        $warehouse = Warehouse::factory()->create();
        
        $manager = WarehouseManager::create([
            'first_name' => 'احمد',
            'last_name' => 'احمدی',
            'national_id' => '1234567890',
            'employee_id' => 'EMP001',
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'mobile' => '09123456789',
            'address' => 'تهران، خیابان ولیعصر',
            'hire_date' => '2020-01-01',
            'employment_status' => 'active',
            'position' => 'انباردار',
            'warehouse_id' => $warehouse->id,
        ]);

        $this->assertDatabaseHas('warehouse_managers', [
            'first_name' => 'احمد',
            'last_name' => 'احمدی',
            'national_id' => '1234567890',
            'employee_id' => 'EMP001',
        ]);

        $this->assertEquals('احمد احمدی', $manager->full_name);
        $this->assertEquals('مرد', $manager->gender_label);
        $this->assertEquals('فعال', $manager->employment_status_label);
    }

    public function test_warehouse_manager_relationships(): void
    {
        $warehouse = Warehouse::factory()->create();
        
        $manager = WarehouseManager::create([
            'first_name' => 'احمد',
            'last_name' => 'احمدی',
            'national_id' => '1234567890',
            'employee_id' => 'EMP001',
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'mobile' => '09123456789',
            'address' => 'تهران، خیابان ولیعصر',
            'hire_date' => '2020-01-01',
            'employment_status' => 'active',
            'position' => 'انباردار',
            'warehouse_id' => $warehouse->id,
        ]);

        $this->assertInstanceOf(Warehouse::class, $manager->warehouse);
        $this->assertEquals($warehouse->id, $manager->warehouse->id);
    }

    public function test_warehouse_manager_scopes(): void
    {
        $warehouse = Warehouse::factory()->create();
        
        // Create active manager
        WarehouseManager::create([
            'first_name' => 'احمد',
            'last_name' => 'احمدی',
            'national_id' => '1234567890',
            'employee_id' => 'EMP001',
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'mobile' => '09123456789',
            'address' => 'تهران، خیابان ولیعصر',
            'hire_date' => '2020-01-01',
            'employment_status' => 'active',
            'position' => 'انباردار',
            'warehouse_id' => $warehouse->id,
        ]);

        // Create inactive manager
        WarehouseManager::create([
            'first_name' => 'علی',
            'last_name' => 'علی‌زاده',
            'national_id' => '0987654321',
            'employee_id' => 'EMP002',
            'birth_date' => '1985-01-01',
            'gender' => 'male',
            'mobile' => '09123456788',
            'address' => 'تهران، خیابان ولیعصر',
            'hire_date' => '2018-01-01',
            'employment_status' => 'inactive',
            'position' => 'انباردار',
            'warehouse_id' => $warehouse->id,
        ]);

        $activeManagers = WarehouseManager::active()->get();
        $this->assertCount(1, $activeManagers);
        $this->assertEquals('احمد احمدی', $activeManagers->first()->full_name);
    }
}
