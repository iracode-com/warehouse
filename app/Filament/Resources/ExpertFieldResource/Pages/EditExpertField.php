<?php

namespace App\Filament\Resources\ExpertFieldResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\ExpertFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpertField extends EditRecord
{
    protected static string $resource = ExpertFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
