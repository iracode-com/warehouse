<?php

namespace App\Filament\Resources\ProductProfileResource\Pages;

use App\Filament\Resources\ProductProfileResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

class EditProductProfile extends EditRecord
{
    protected static string $resource = ProductProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
