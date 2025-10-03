<?php

namespace App\Filament\Resources\PersonnelFileTypeResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\PersonnelFileTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonnelFileType extends EditRecord
{
    protected static string $resource = PersonnelFileTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
