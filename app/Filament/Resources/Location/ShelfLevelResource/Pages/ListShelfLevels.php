<?php

namespace App\Filament\Resources\Location\ShelfLevelResource\Pages;

use App\Filament\Resources\Location\ShelfLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShelfLevels extends ListRecords
{
    protected static string $resource = ShelfLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
