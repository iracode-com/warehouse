<?php

namespace App\Filament\Resources\PersonnelFileTypeResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Support\Enums\Width;
use App\Filament\Resources\PersonnelFileTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonnelFileTypes extends ListRecords
{
    protected static string $resource = PersonnelFileTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->modalWidth(Width::Medium),
        ];
    }
}
