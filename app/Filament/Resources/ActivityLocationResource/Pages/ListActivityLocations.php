<?php

namespace App\Filament\Resources\ActivityLocationResource\Pages;

use App\Filament\Resources\ActivityLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivityLocations extends ListRecords
{
    protected static string $resource = ActivityLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
