<?php

namespace App\Filament\Resources\ProductProfileResource\Pages;

use App\Filament\Resources\ProductProfileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductProfile extends CreateRecord
{
    protected static string $resource = ProductProfileResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
