<?php

namespace App\Filament\Resources\Location\RackResource\Pages;

use App\Filament\Resources\Location\RackResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRack extends EditRecord
{
    protected static string $resource = RackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
