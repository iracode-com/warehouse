<?php

namespace App\Filament\Resources\WarehouseSheds\Pages;

use App\Filament\Resources\WarehouseSheds\WarehouseShedResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWarehouseShed extends EditRecord
{
    protected static string $resource = WarehouseShedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
