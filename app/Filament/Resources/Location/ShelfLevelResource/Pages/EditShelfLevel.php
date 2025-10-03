<?php

namespace App\Filament\Resources\Location\ShelfLevelResource\Pages;

use App\Filament\Resources\Location\ShelfLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShelfLevel extends EditRecord
{
    protected static string $resource = ShelfLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
