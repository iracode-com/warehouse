<?php

namespace App\Filament\Resources\Location\RackResource\Pages;

use App\Filament\Resources\Location\RackResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRack extends ViewRecord
{
    protected static string $resource = RackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
