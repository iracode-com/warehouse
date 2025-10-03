<?php

namespace App\Filament\Resources\ActivityLocationResource\Pages;

use App\Filament\Resources\ActivityLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActivityLocation extends EditRecord
{
    protected static string $resource = ActivityLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
