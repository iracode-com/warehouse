<?php

namespace App\Filament\Resources\PersonnelResource\Pages;

use App\Filament\Resources\PersonnelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePersonnel extends CreateRecord
{
    protected static string $resource = PersonnelResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $record = new ($this->getModel())($data);

        $record->save();

        if($data['personnel_files'] && count($data['personnel_files']) > 0)
            $record->personnel_files()->createMany($data['personnel_files']);

        if($data['personnel_contact_informations'] && count($data['personnel_contact_informations']) > 0)
            $record->personnel_contact_informations()->createMany($data['personnel_contact_informations']);

        return $record;
    }
}
