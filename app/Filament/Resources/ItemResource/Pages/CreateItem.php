<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use App\Models\Item;
use Filament\Resources\Pages\CreateRecord;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // اگر شماره سریال وجود دارد اما بارکد و QR Code ندارند، تولید کن
        if (!empty($data['serial_number'])) {
            if (empty($data['barcode'])) {
                $data['barcode'] = Item::generateBarcode($data['serial_number']);
            }
            if (empty($data['qr_code'])) {
                $data['qr_code'] = Item::generateQRCode($data['serial_number']);
            }
        }

        return $data;
    }
}
