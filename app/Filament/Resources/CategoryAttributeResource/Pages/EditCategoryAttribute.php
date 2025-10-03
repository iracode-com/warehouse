<?php

namespace App\Filament\Resources\CategoryAttributeResource\Pages;

use App\Filament\Resources\CategoryAttributeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryAttribute extends EditRecord
{
    protected static string $resource = CategoryAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
