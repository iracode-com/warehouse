<?php

namespace App\Filament\Resources\CooperationTypeResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Support\Enums\Width;
use App\Filament\Resources\CooperationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCooperationTypes extends ListRecords
{
    protected static string $resource = CooperationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->modalWidth(Width::Medium),
        ];
    }
}
