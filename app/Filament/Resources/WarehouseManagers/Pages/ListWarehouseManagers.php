<?php

namespace App\Filament\Resources\WarehouseManagers\Pages;

use App\Filament\Resources\WarehouseManagers\WarehouseManagerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWarehouseManagers extends ListRecords
{
    protected static string $resource = WarehouseManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
