<?php

namespace App\Filament\Resources\WarehouseResource\Pages;

use App\Filament\Resources\WarehouseResource;
use App\Models\WarehouseUsageType;
use Filament\Resources\Pages\CreateRecord;

class CreateWarehouse extends CreateRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function afterCreate(): void
    {
        $usageTypes = $this->data['usage_types'] ?? [];
        
        foreach ($usageTypes as $usageType) {
            WarehouseUsageType::create([
                'warehouse_id' => $this->record->id,
                'usage_type' => $usageType,
            ]);
        }
    }
}
