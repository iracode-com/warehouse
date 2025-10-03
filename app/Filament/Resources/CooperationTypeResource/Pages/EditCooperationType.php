<?php

namespace App\Filament\Resources\CooperationTypeResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\CooperationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCooperationType extends EditRecord
{
    protected static string $resource = CooperationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
