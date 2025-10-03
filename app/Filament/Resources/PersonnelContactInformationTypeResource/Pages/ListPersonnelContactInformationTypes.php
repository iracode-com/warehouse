<?php

namespace App\Filament\Resources\PersonnelContactInformationTypeResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Support\Enums\Width;
use App\Filament\Resources\PersonnelContactInformationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonnelContactInformationTypes extends ListRecords
{
    protected static string $resource = PersonnelContactInformationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->modalWidth(Width::Medium),
        ];
    }
}
