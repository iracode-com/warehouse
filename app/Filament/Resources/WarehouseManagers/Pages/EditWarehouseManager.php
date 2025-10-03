<?php

namespace App\Filament\Resources\WarehouseManagers\Pages;

use App\Filament\Resources\WarehouseManagers\WarehouseManagerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWarehouseManager extends EditRecord
{
    protected static string $resource = WarehouseManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
