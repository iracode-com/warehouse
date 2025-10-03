<?php

namespace App\Filament\Resources\ProductProfileResource\Pages;

use App\Filament\Resources\ProductProfileResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListProductProfiles extends ListRecords
{
    protected static string $resource = ProductProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
