<?php

namespace App\Filament\Resources\Location\RackInspectionResource\Pages;

use App\Filament\Resources\Location\RackInspectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRackInspection extends ViewRecord
{
    protected static string $resource = RackInspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
