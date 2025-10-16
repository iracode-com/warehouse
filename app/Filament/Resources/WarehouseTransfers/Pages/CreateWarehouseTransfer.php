<?php

namespace App\Filament\Resources\WarehouseTransfers\Pages;

use App\Filament\Resources\WarehouseTransfers\WarehouseTransferResource;
use App\Models\Document;
use Filament\Resources\Pages\CreateRecord;

class CreateWarehouseTransfer extends CreateRecord
{
    protected static string $resource = WarehouseTransferResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['document_type'] = Document::TYPE_TRANSFER;
        return $data;
    }
}
