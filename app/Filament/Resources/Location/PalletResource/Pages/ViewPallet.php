<?php

namespace App\Filament\Resources\Location\PalletResource\Pages;

use App\Filament\Resources\Location\PalletResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPallet extends ViewRecord
{
    protected static string $resource = PalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
