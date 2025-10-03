<?php

namespace App\Filament\Resources\Location\ShelfLevelResource\Pages;

use App\Filament\Resources\Location\ShelfLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewShelfLevel extends ViewRecord
{
    protected static string $resource = ShelfLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
