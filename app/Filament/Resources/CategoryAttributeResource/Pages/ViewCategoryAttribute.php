<?php

namespace App\Filament\Resources\CategoryAttributeResource\Pages;

use App\Filament\Resources\CategoryAttributeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCategoryAttribute extends ViewRecord
{
    protected static string $resource = CategoryAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
