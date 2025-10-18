<?php

namespace App\Filament\Resources\ProductProfileResource\Pages;

use App\Filament\Resources\ProductProfileResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\ViewEntry;

class ViewProductProfile extends ViewRecord
{
    protected static string $resource = ProductProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make(__('product-profile.sections.basic_info'))
                    ->description(__('product-profile.sections.basic_info_desc'))
                    ->icon('heroicon-o-identification')
                    ->iconColor('primary')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label(__('product-profile.fields.name'))
                                    ->weight('bold')
                                    ->size('lg'),

                                TextEntry::make('sku')
                                    ->label(__('product-profile.fields.sku'))
                                    ->badge()
                                    ->color('success'),
                            ]),

                        Grid::make(4)
                            ->schema([
                                TextEntry::make('category.name')
                                    ->label(__('product-profile.fields.category_id'))
                                    ->badge()
                                    ->color('info'),

                                TextEntry::make('category.category_type')
                                    ->label(__('product-profile.fields.category_type'))
                                    ->getStateUsing(function ($record) {
                                        return $record->category && $record->category->category_type ? __('product-profile.options.category_types.' . $record->category->category_type) : '';
                                    })
                                    ->badge()
                                    ->color('warning'),

                                TextEntry::make('packagingType.name')
                                    ->label(__('product-profile.fields.packaging_type'))
                                    ->badge()
                                    ->color('info'),

                                TextEntry::make('product_type')
                                    ->label(__('product-profile.fields.product_type'))
                                    ->getStateUsing(function ($record) {
                                        return $record->product_type ? __('product-profile.options.product_types.' . $record->product_type) : '';
                                    })
                                    ->badge()
                                    ->color('secondary'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('brand.name')
                                    ->label(__('product-profile.fields.brand'))
                                    ->placeholder('تعریف نشده'),

                                TextEntry::make('model')
                                    ->label(__('product-profile.fields.model'))
                                    ->placeholder('تعریف نشده'),
                            ]),

                        TextEntry::make('description')
                            ->label(__('product-profile.fields.description'))
                            ->placeholder('توضیحی ثبت نشده')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.codes_barcodes'))
                    ->description(__('product-profile.sections.codes_barcodes_desc'))
                    ->icon('heroicon-o-qr-code')
                    ->iconColor('success')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('barcode')
                                    ->label(__('product-profile.fields.barcode'))
                                    ->placeholder('تعریف نشده')
                                    ->badge()
                                    ->color('info')
                                    ->copyable()
                                    ->copyMessage('بارکد کپی شد'),

                                TextEntry::make('qr_code')
                                    ->label(__('product-profile.fields.qr_code'))
                                    ->placeholder('تعریف نشده')
                                    ->badge()
                                    ->color('success')
                                    ->copyable()
                                    ->copyMessage('کد QR کپی شد'),
                            ]),
                        
                        Grid::make(2)
                            ->schema([
                                ImageEntry::make('barcode_image')
                                    ->label('تصویر بارکد')
                                    ->getStateUsing(fn ($record) => $record->barcode_image)
                                    ->visible(fn ($record) => !empty($record->barcode)),

                                ImageEntry::make('qr_code_image')
                                    ->label('تصویر کد QR')
                                    ->getStateUsing(fn ($record) => $record->qr_code_image)
                                    ->visible(fn ($record) => !empty($record->qr_code)),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.units_pricing'))
                    ->description(__('product-profile.sections.units_pricing_desc'))
                    ->icon('heroicon-o-scale')
                    ->iconColor('success')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('unit_of_measure')
                                    ->label(__('product-profile.fields.unit_of_measure'))
                                    ->getStateUsing(function ($record) {
                                        return $record->unit_of_measure ? __('product-profile.options.units.' . $record->unit_of_measure) : '';
                                    })
                                    ->badge(),

                                TextEntry::make('primary_unit')
                                    ->label(__('product-profile.fields.primary_unit'))
                                    ->getStateUsing(function ($record) {
                                        return $record->primary_unit ? __('product-profile.options.units.' . $record->primary_unit) : '';
                                    })
                                    ->badge(),

                                TextEntry::make('secondary_unit')
                                    ->label(__('product-profile.fields.secondary_unit'))
                                    ->getStateUsing(function ($record) {
                                        return $record->secondary_unit ? __('product-profile.options.units.' . $record->secondary_unit) : '';
                                    })
                                    ->badge(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('pricing_method')
                                    ->label(__('product-profile.fields.pricing_method'))
                                    ->getStateUsing(function ($record) {
                                        return $record->pricing_method ? __('product-profile.options.pricing_methods.' . $record->pricing_method) : '';
                                    })
                                    ->badge(),

                                TextEntry::make('standard_cost')
                                    ->label(__('product-profile.fields.standard_cost'))
                                    ->money('IRR')
                                    ->placeholder('تعریف نشده'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.physical_specs'))
                    ->description(__('product-profile.sections.physical_specs_desc'))
                    ->icon('heroicon-o-cube')
                    ->iconColor('warning')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('weight')
                                    ->label(__('product-profile.fields.weight'))
                                    ->suffix(' کیلوگرم')
                                    ->placeholder('تعریف نشده'),

                                TextEntry::make('length')
                                    ->label(__('product-profile.fields.length'))
                                    ->suffix(' سانتی‌متر')
                                    ->placeholder('تعریف نشده'),

                                TextEntry::make('width')
                                    ->label(__('product-profile.fields.width'))
                                    ->suffix(' سانتی‌متر')
                                    ->placeholder('تعریف نشده'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('height')
                                    ->label(__('product-profile.fields.height'))
                                    ->suffix(' سانتی‌متر')
                                    ->placeholder('تعریف نشده'),

                                TextEntry::make('volume')
                                    ->label(__('product-profile.fields.volume'))
                                    ->suffix(' لیتر')
                                    ->placeholder('تعریف نشده'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.features_status'))
                    ->description(__('product-profile.sections.features_status_desc'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->iconColor('info')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('feature_1')
                                    ->label(__('product-profile.fields.feature_1'))
                                    ->placeholder('تعریف نشده'),

                                TextEntry::make('feature_2')
                                    ->label(__('product-profile.fields.feature_2'))
                                    ->placeholder('تعریف نشده'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('has_expiry_date')
                                    ->label(__('product-profile.fields.has_expiry_date'))
                                    ->getStateUsing(fn ($record) => $record->has_expiry_date ? 'دارد' : 'ندارد')
                                    ->badge()
                                    ->color(fn ($record) => $record->has_expiry_date ? 'success' : 'gray'),

                                TextEntry::make('consumption_status')
                                    ->label(__('product-profile.fields.consumption_status'))
                                    ->getStateUsing(function ($record) {
                                        return $record->consumption_status ? __('product-profile.options.consumption_statuses.' . $record->consumption_status) : '';
                                    })
                                    ->badge()
                                    ->color(fn ($record) => match($record->consumption_status) {
                                        'high_consumption' => 'success',
                                        'strategic' => 'warning',
                                        'low_consumption' => 'info',
                                        'stagnant' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('is_flammable')
                                    ->label(__('product-profile.fields.is_flammable'))
                                    ->getStateUsing(fn ($record) => $record->is_flammable ? 'دارد' : 'ندارد')
                                    ->badge()
                                    ->color(fn ($record) => $record->is_flammable ? 'danger' : 'gray'),

                                TextEntry::make('has_return_policy')
                                    ->label(__('product-profile.fields.has_return_policy'))
                                    ->getStateUsing(fn ($record) => $record->has_return_policy ? 'دارد' : 'ندارد')
                                    ->badge()
                                    ->color(fn ($record) => $record->has_return_policy ? 'success' : 'gray'),
                            ]),

                        TextEntry::make('product_address')
                            ->label(__('product-profile.fields.product_address'))
                            ->placeholder('تعریف نشده')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                // Section::make(__('product-profile.sections.images'))
                //     ->description(__('product-profile.sections.images_desc'))
                //     ->icon('heroicon-o-photo')
                //     ->iconColor('secondary')
                //     ->schema([
                //         ViewEntry::make('images_gallery')
                //             ->label(__('product-profile.fields.images'))
                //             ->view('filament.components.image-gallery')
                //             ->getStateUsing(function ($record) {
                //                 if (empty($record->images)) {
                //                     return [];
                //                 }
                                
                //                 $images = is_array($record->images) ? $record->images : [];
                //                 return $images;
                //             })
                //             ->visible(fn ($record) => !empty($record->images))
                //             ->columnSpanFull(),
                //     ])
                //     ->collapsible(),

                Section::make(__('product-profile.sections.status_settings'))
                    ->description(__('product-profile.sections.status_settings_desc'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->iconColor('gray')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('status')
                                    ->label(__('product-profile.fields.status'))
                                    ->getStateUsing(function ($record) {
                                        return $record->status_label;
                                    })
                                    ->badge()
                                    ->color(fn ($record) => $record->status_color),

                                TextEntry::make('is_active')
                                    ->label(__('product-profile.fields.is_active'))
                                    ->getStateUsing(fn ($record) => $record->is_active ? 'فعال' : 'غیرفعال')
                                    ->badge()
                                    ->color(fn ($record) => $record->is_active ? 'success' : 'danger'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
