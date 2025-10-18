<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ViewItem extends ViewRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make('اطلاعات کالا')
                    ->description('اطلاعات کلی و شناسایی کالا')
                    ->icon('heroicon-o-cube')
                    ->iconColor('primary')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('productProfile.name')
                                    ->label('نام کالا')
                                    ->weight('bold')
                                    ->size('lg'),

                                TextEntry::make('productProfile.sku')
                                    ->label('کد کالا (SKU)')
                                    ->badge()
                                    ->color('success'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextEntry::make('serial_number')
                                    ->label('شماره سریال')
                                    ->badge()
                                    ->color('info'),

                                TextEntry::make('barcode')
                                    ->label('بارکد')
                                    ->badge()
                                    ->color('warning')
                                    ->copyable()
                                    ->copyMessage('بارکد کپی شد'),

                                TextEntry::make('qr_code')
                                    ->label('QR Code')
                                    ->badge()
                                    ->color('success')
                                    ->copyable()
                                    ->copyMessage('QR Code کپی شد'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('status')
                                    ->label('وضعیت')
                                    ->getStateUsing(function ($record) {
                                        return $record->status_label;
                                    })
                                    ->badge()
                                    ->color(fn ($record) => $record->status_color),

                                TextEntry::make('is_active')
                                    ->label('فعال')
                                    ->getStateUsing(fn ($record) => $record->is_active ? 'فعال' : 'غیرفعال')
                                    ->badge()
                                    ->color(fn ($record) => $record->is_active ? 'success' : 'danger'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('کدها و بارکدها')
                    ->description('تصاویر بارکد و QR Code')
                    ->icon('heroicon-o-qr-code')
                    ->iconColor('success')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                ImageEntry::make('barcode_image')
                                    ->label('تصویر بارکد')
                                    ->getStateUsing(fn ($record) => $record->barcode_image)
                                    ->visible(fn ($record) => !empty($record->barcode))
                                    ->size(200)
                                    ->height(100),

                                ImageEntry::make('qr_code_image')
                                    ->label('تصویر QR Code')
                                    ->getStateUsing(fn ($record) => $record->qr_code_image)
                                    ->visible(fn ($record) => !empty($record->qr_code))
                                    ->size(200)
                                    ->height(200),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('موجودی و قیمت‌گذاری')
                    ->description('اطلاعات موجودی و قیمت‌ها')
                    ->icon('heroicon-o-currency-dollar')
                    ->iconColor('warning')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('current_stock')
                                    ->label('موجودی فعلی')
                                    ->numeric()
                                    ->badge()
                                    ->color(fn ($record) => $record->stock_status_color),

                                TextEntry::make('unit_cost')
                                    ->label('هزینه واحد')
                                    ->money('IRR')
                                    ->placeholder('تعریف نشده'),

                                TextEntry::make('selling_price')
                                    ->label('قیمت فروش')
                                    ->money('IRR')
                                    ->placeholder('تعریف نشده'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('تاریخ‌ها')
                    ->description('تاریخ‌های مهم کالا')
                    ->icon('heroicon-o-calendar')
                    ->iconColor('info')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('manufacture_date')
                                    ->label('تاریخ تولید')
                                    ->jalaliDate()
                                    ->placeholder('تعریف نشده'),

                                TextEntry::make('expiry_date')
                                    ->label('تاریخ انقضا')
                                    ->jalaliDate()
                                    ->placeholder('تعریف نشده'),

                                TextEntry::make('purchase_date')
                                    ->label('تاریخ خرید')
                                    ->jalaliDate()
                                    ->placeholder('تعریف نشده'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('مکان‌یابی')
                    ->description('موقعیت کالا در انبار')
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('secondary')
                    ->schema([
                        TextEntry::make('warehouse.title')
                            ->label('انبار')
                            ->badge()
                            ->color('info')
                            ->placeholder('تعریف نشده'),
                    ])
                    ->collapsible(),
            ]);
    }
}