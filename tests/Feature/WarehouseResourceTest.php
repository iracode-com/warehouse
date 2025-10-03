<?php

namespace Tests\Feature;

use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WarehouseResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user for authentication
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_can_list_warehouses(): void
    {
        $warehouses = Warehouse::factory()->count(3)->create();

        $this->get('/admin/warehouses')
            ->assertStatus(200)
            ->assertSee($warehouses->first()->title);
    }

    public function test_can_create_warehouse(): void
    {
        $warehouseData = [
            'title' => 'انبار جدید',
            'manager_name' => 'احمد احمدی',
            'manager_phone' => '09123456789',
            'usage_type' => 'emergency',
            'province' => 'تهران',
            'branch' => 'شعبه مرکزی',
            'base' => 'پایگاه اصلی',
            'warehouse_info' => 'اطلاعات انبار جدید',
            'establishment_year' => 1400,
            'construction_year' => 1400,
            'population_census' => 100000,
            'ownership_type' => 'owned',
            'area' => 2000.00,
            'structure_type' => 'concrete',
            'warehouse_count' => 3,
            'warehouse_insurance' => 'yes',
            'building_insurance' => 'yes',
            'ram_rack' => 'yes',
            'telephone' => 'yes',
            'address' => 'تهران، خیابان آزادی',
        ];

        $this->post('/admin/warehouses', $warehouseData)
            ->assertRedirect();

        $this->assertDatabaseHas('warehouses', [
            'title' => 'انبار جدید',
            'manager_name' => 'احمد احمدی',
        ]);
    }

    public function test_can_view_warehouse(): void
    {
        $warehouse = Warehouse::factory()->create();

        $this->get("/admin/warehouses/{$warehouse->id}")
            ->assertStatus(200)
            ->assertSee($warehouse->title);
    }

    public function test_can_edit_warehouse(): void
    {
        $warehouse = Warehouse::factory()->create([
            'title' => 'انبار قدیمی',
        ]);

        $this->get("/admin/warehouses/{$warehouse->id}/edit")
            ->assertStatus(200)
            ->assertSee($warehouse->title);
    }

    public function test_can_update_warehouse(): void
    {
        $warehouse = Warehouse::factory()->create([
            'title' => 'انبار قدیمی',
        ]);

        $updateData = [
            'title' => 'انبار به‌روزرسانی شده',
            'manager_name' => $warehouse->manager_name,
            'manager_phone' => $warehouse->manager_phone,
            'usage_type' => $warehouse->usage_type,
            'province' => $warehouse->province,
            'branch' => $warehouse->branch,
            'base' => $warehouse->base,
            'warehouse_info' => $warehouse->warehouse_info,
            'establishment_year' => $warehouse->establishment_year,
            'construction_year' => $warehouse->construction_year,
            'population_census' => $warehouse->population_census,
            'ownership_type' => $warehouse->ownership_type,
            'area' => $warehouse->area,
            'structure_type' => $warehouse->structure_type,
            'warehouse_count' => $warehouse->warehouse_count,
            'warehouse_insurance' => $warehouse->warehouse_insurance,
            'building_insurance' => $warehouse->building_insurance,
            'ram_rack' => $warehouse->ram_rack,
            'telephone' => $warehouse->telephone,
            'address' => $warehouse->address,
        ];

        $this->put("/admin/warehouses/{$warehouse->id}", $updateData)
            ->assertRedirect();

        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id,
            'title' => 'انبار به‌روزرسانی شده',
        ]);
    }

    public function test_can_delete_warehouse(): void
    {
        $warehouse = Warehouse::factory()->create();

        $this->delete("/admin/warehouses/{$warehouse->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('warehouses', [
            'id' => $warehouse->id,
        ]);
    }
}
