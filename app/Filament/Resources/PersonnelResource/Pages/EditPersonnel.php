<?php

namespace App\Filament\Resources\PersonnelResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\PersonnelResource;
use App\Models\Personnel\PersonnelContactInformation as PersonnelPersonnelContactInformation;
use App\Models\Personnel\PersonnelFile as PersonnelPersonnelFile;
use App\Models\PersonnelContactInformation;
use App\Models\PersonnelFile;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPersonnel extends EditRecord
{
    protected static string $resource = PersonnelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['personnel_contact_informations'] = PersonnelPersonnelContactInformation::where('personnel_id',$data['id'])->get();
        $data['personnel_files'] = PersonnelPersonnelFile::where('personnel_id',$data['id'])->get();
        return $data;
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        if($data['personnel_files'] && count($data['personnel_files']) > 0){
            $record->personnel_files()->delete();
            $record->personnel_files()->createMany($data['personnel_files']);
        }

        if($data['personnel_contact_informations'] && count($data['personnel_contact_informations']) > 0){
            $record->personnel_contact_informations()->delete();
            $record->personnel_contact_informations()->createMany($data['personnel_contact_informations']);
        }

        return $record;
    }
}
