<?php

namespace App\Filament\Resources\ExpertFieldResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Support\Enums\Width;
use App\Filament\Resources\ExpertFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpertFields extends ListRecords
{
    protected static string $resource = ExpertFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->modalWidth(Width::Medium),
        ];
    }
}
