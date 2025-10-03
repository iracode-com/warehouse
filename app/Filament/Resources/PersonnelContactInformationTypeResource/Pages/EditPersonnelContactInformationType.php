<?php

namespace App\Filament\Resources\PersonnelContactInformationTypeResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\PersonnelContactInformationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonnelContactInformationType extends EditRecord
{
    protected static string $resource = PersonnelContactInformationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
