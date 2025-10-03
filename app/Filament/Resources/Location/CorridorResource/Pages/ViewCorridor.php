<?php

namespace App\Filament\Resources\Location\CorridorResource\Pages;

use App\Filament\Resources\Location\CorridorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCorridor extends ViewRecord
{
    protected static string $resource = CorridorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
