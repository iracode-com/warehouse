<?php

namespace App\Filament\Resources\WarehouseResource\Pages;

use App\Filament\Resources\WarehouseResource;
use App\Models\WarehouseUsageType;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWarehouse extends EditRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['usage_types'] = $this->record->usageTypes()->pluck('usage_type')->toArray();
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Delete existing usage types
        $this->record->usageTypes()->delete();
        
        // Create new usage types
        $usageTypes = $this->data['usage_types'] ?? [];
        
        foreach ($usageTypes as $usageType) {
            WarehouseUsageType::create([
                'warehouse_id' => $this->record->id,
                'usage_type' => $usageType,
            ]);
        }
    }
}
