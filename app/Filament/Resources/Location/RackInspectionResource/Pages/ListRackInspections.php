<?php

namespace App\Filament\Resources\Location\RackInspectionResource\Pages;

use App\Filament\Resources\Location\RackInspectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRackInspections extends ListRecords
{
    protected static string $resource = RackInspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
