<?php

namespace App\Filament\Resources\Location\RackInspectionResource\Pages;

use App\Filament\Resources\Location\RackInspectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRackInspection extends EditRecord
{
    protected static string $resource = RackInspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
