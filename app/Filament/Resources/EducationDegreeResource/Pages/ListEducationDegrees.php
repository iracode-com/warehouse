<?php

namespace App\Filament\Resources\EducationDegreeResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Support\Enums\Width;
use App\Filament\Resources\EducationDegreeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEducationDegrees extends ListRecords
{
    protected static string $resource = EducationDegreeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->modalWidth(Width::Medium),
        ];
    }
}
