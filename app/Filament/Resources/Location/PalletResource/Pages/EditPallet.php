<?php

namespace App\Filament\Resources\Location\PalletResource\Pages;

use App\Filament\Resources\Location\PalletResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPallet extends EditRecord
{
    protected static string $resource = PalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
