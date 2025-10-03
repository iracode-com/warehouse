<?php

namespace App\Filament\Resources\WarehouseSheds\Pages;

use App\Filament\Resources\WarehouseSheds\WarehouseShedResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWarehouseSheds extends ListRecords
{
    protected static string $resource = WarehouseShedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
