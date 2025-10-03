<?php

namespace App\Filament\Resources\Location\CorridorResource\Pages;

use App\Filament\Resources\Location\CorridorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCorridors extends ListRecords
{
    protected static string $resource = CorridorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
