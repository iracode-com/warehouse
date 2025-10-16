<?php

namespace App\Filament\Resources\ProductSetResource\Pages;

use App\Filament\Resources\ProductSetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductSet extends EditRecord
{
    protected static string $resource = ProductSetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('product-set.updated');
    }
}
