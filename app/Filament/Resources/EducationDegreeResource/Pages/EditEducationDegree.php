<?php

namespace App\Filament\Resources\EducationDegreeResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\EducationDegreeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEducationDegree extends EditRecord
{
    protected static string $resource = EducationDegreeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
