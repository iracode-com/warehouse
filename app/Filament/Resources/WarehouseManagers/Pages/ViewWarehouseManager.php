<?php

namespace App\Filament\Resources\WarehouseManagers\Pages;

use App\Filament\Resources\WarehouseManagers\WarehouseManagerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWarehouseManager extends ViewRecord
{
    protected static string $resource = WarehouseManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label(__('warehouse.warehouse_manager.actions.edit'))
                ->icon('heroicon-o-pencil'),
        ];
    }
}
