<?php

namespace App\Filament\Resources\Location\CorridorResource\Pages;

use App\Filament\Resources\Location\CorridorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCorridor extends EditRecord
{
    protected static string $resource = CorridorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
