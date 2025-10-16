<?php

namespace App\Filament\Resources\ProductSetResource\Pages;

use App\Filament\Resources\ProductSetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductSet extends CreateRecord
{
    protected static string $resource = ProductSetResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('product-set.created');
    }
}
