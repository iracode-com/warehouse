<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use App\Models\Item;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // اگر شماره سریال تغییر کرده، کدها را دوباره تولید کن
        if (!empty($data['serial_number']) && $data['serial_number'] !== $this->record->serial_number) {
            $data['barcode'] = Item::generateBarcode($data['serial_number']);
            $data['qr_code'] = Item::generateQRCode($data['serial_number']);
        }

        return $data;
    }
}
