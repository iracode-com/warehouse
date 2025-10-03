<?php

namespace App\Filament\Resources\Location\RackResource\Pages;

use App\Filament\Resources\Location\RackResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRacks extends ListRecords
{
    protected static string $resource = RackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
