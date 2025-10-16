<?php

namespace App\Filament\Resources\BaseResource\Pages;

use App\Filament\Resources\BaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBase extends CreateRecord
{
    protected static string $resource = BaseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
