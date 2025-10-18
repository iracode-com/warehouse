<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use App\Models\Location\Pallet;
use App\Models\Location\Rack;
use App\Models\Location\ShelfLevel;
use App\Models\Location\Zone;
use App\Models\ProductProfile;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-cube';
    }

    public static function getNavigationGroup(): ?string
    {
        return __('item.navigation.group');
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getNavigationLabel(): string
    {
        return __('item.navigation.plural');
    }

    public static function getModelLabel(): string
    {
        return __('item.navigation.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('item.navigation.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make(__('item.sections.product_profile_selection'))
                    ->description(__('item.sections.product_profile_selection_desc'))
                    ->icon('heroicon-o-identification')
                    ->iconColor('primary')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('product_profile_id')
                            ->label(__('item.fields.product_profile_id'))
                            ->relationship('productProfile', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn(ProductProfile $record): string => "{$record->name} ({$record->sku})")
                            ->helperText(__('item.fields.product_profile_id_helper')),
                    ]),

                Section::make(__('item.sections.item_info'))
                    ->description(__('item.sections.item_info_desc'))
                    ->icon('heroicon-o-cube')
                    ->iconColor('success')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('serial_number')
                                    ->label(__('item.fields.serial_number'))
                                    ->maxLength(255)
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->live()
                                    ->afterStateUpdated(function ($set, $state) {
                                        if ($state) {
                                            $barcode = Item::generateBarcode($state);
                                            $qrCode = Item::generateQRCode($state);
                                            $set('barcode', $barcode);
                                            $set('qr_code', $qrCode);
                                        }
                                    })
                                    ->helperText(__('item.fields.serial_number_helper'))
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('barcode')
                                    ->label('بارکد')
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated()
                                    ->prefixIcon('heroicon-o-qr-code')
                                    ->helperText('بارکد خودکار بر اساس شماره سریال')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('qr_code')
                                    ->label('QR Code')
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated()
                                    ->prefixIcon('heroicon-o-qr-code')
                                    ->helperText('QR Code خودکار بر اساس شماره سریال')
                                    ->columnSpan(1),
                            ]),

                        Grid::make(1)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label(__('item.fields.status'))
                                    ->options(__('item.status_options'))
                                    ->default(Item::STATUS_ACTIVE)
                                    ->required()
                                    ->helperText(__('item.fields.status_helper')),
                            ]),
                    ]),

                Forms\Components\Select::make('source_document_id')
                    ->label('سند مبدا')
                    ->relationship('sourceDocument', 'document_number')
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => $record->document_number . ' - ' . $record->getTypeLabelAttribute() . ' (' . $record->document_date->format('Y/m/d') . ')'
                    )
                    ->searchable(['document_number'])
                    ->preload()
                    ->placeholder('انتخاب سند مبدا (اختیاری)')
                    ->helperText('مشخص می‌کند این قلم از کدام سند ورودی آمده')
                    ->columnSpanFull(),

                Section::make(__('item.sections.inventory_pricing'))
                    ->description(__('item.sections.inventory_pricing_desc'))
                    ->icon('heroicon-o-currency-dollar')
                    ->iconColor('warning')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Forms\Components\TextInput::make('current_stock')
                                    ->label(__('item.fields.current_stock'))
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->helperText(__('item.fields.current_stock_helper')),

                                // Forms\Components\TextInput::make('min_stock')
                                //     ->label(__('item.fields.min_stock'))
                                //     ->numeric()
                                //     ->default(0)
                                //     ->helperText(__('item.fields.min_stock_helper')),

                                // Forms\Components\TextInput::make('max_stock')
                                //     ->label(__('item.fields.max_stock'))
                                //     ->numeric()
                                //     ->helperText(__('item.fields.max_stock_helper')),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('unit_cost')
                                    ->label(__('item.fields.unit_cost'))
                                    ->numeric()
                                    ->required()
                                    ->prefix('ریال')
                                    ->helperText(__('item.fields.unit_cost_helper')),

                                Forms\Components\TextInput::make('selling_price')
                                    ->label(__('item.fields.selling_price'))
                                    ->numeric()
                                    ->prefix('ریال')
                                    ->helperText(__('item.fields.selling_price_helper')),
                            ]),
                    ]),

                Section::make(__('item.sections.dates'))
                    ->description(__('item.sections.dates_desc'))
                    ->icon('heroicon-o-calendar')
                    ->iconColor('info')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\DatePicker::make('manufacture_date')
                                    ->label(__('item.fields.manufacture_date'))
                                    ->helperText(__('item.fields.manufacture_date_helper'))
                                    ->required()
                                    ->jalali(),

                                Forms\Components\DatePicker::make('expiry_date')
                                    ->label(__('item.fields.expiry_date'))
                                    ->helperText(__('item.fields.expiry_date_helper'))
                                    ->required()
                                    ->jalali(),

                                Forms\Components\DatePicker::make('purchase_date')
                                    ->label(__('item.fields.purchase_date'))
                                    ->helperText(__('item.fields.purchase_date_helper'))
                                    ->required()
                                    ->jalali(),
                            ]),
                    ]),

                Section::make(__('item.sections.location'))
                    ->description(__('item.sections.location_desc'))
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('secondary')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Forms\Components\Select::make('warehouse_id')
                                    ->label(__('item.fields.warehouse_id'))
                                    ->relationship('warehouse', 'title')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->helperText(__('item.fields.warehouse_id_helper')),

                                // Forms\Components\Select::make('zone_id')
                                //     ->label(__('item.fields.zone_id'))
                                //     ->relationship('zone', 'name')
                                //     ->searchable()
                                //     ->preload()
                                //     ->helperText(__('item.fields.zone_id_helper')),
                            ]),

                        // Grid::make(3)
                        //     ->schema([
                        //         Forms\Components\Select::make('rack_id')
                        //             ->label(__('item.fields.rack_id'))
                        //             ->relationship('rack', 'name')
                        //             ->searchable()
                        //             ->preload()
                        //             ->helperText(__('item.fields.rack_id_helper')),

                        //         Forms\Components\Select::make('shelf_level_id')
                        //             ->label(__('item.fields.shelf_level_id'))
                        //             ->relationship('shelfLevel', 'name')
                        //             ->searchable()
                        //             ->preload()
                        //             ->helperText(__('item.fields.shelf_level_id_helper')),

                        //         Forms\Components\Select::make('pallet_id')
                        //             ->label(__('item.fields.pallet_id'))
                        //             ->relationship('pallet', 'name')
                        //             ->searchable()
                        //             ->preload()
                        //             ->helperText(__('item.fields.pallet_id_helper')),
                        //     ]),
                    ]),

                Section::make(__('item.sections.settings'))
                    ->description(__('item.sections.settings_desc'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->iconColor('gray')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('item.fields.is_active'))
                            ->default(true)
                            ->helperText(__('item.fields.is_active_helper')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('productProfile.sku')
                    ->label(__('item.table.sku'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('productProfile.name')
                    ->label(__('item.fields.product_profile_id'))
                    ->searchable()
                    ->sortable()
                    ->description(fn(Item $record): string => $record->productProfile?->brand ? "برند: {$record->productProfile->brand}" : ''),

                Tables\Columns\TextColumn::make('serial_number')
                    ->label(__('item.table.serial_number'))
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\ImageColumn::make('barcode_image')
                    ->label(__('product-profile.fields.barcode'))
                    ->getStateUsing(fn($record) => $record->barcode_image)
                    ->size(100)
                    ->height(40)
                    ->toggleable(),

                Tables\Columns\ImageColumn::make('qr_code_image')
                    ->label('QR Code')
                    ->getStateUsing(fn($record) => $record->qr_code_image)
                    ->size(60)
                    ->height(60)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('current_stock')
                    ->label(__('item.table.current_stock'))
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn(Item $record): string => $record->stock_status_color),

                Tables\Columns\TextColumn::make('unit_cost')
                    ->label(__('item.table.unit_cost'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('selling_price')
                    ->label(__('item.table.selling_price'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('warehouse.title')
                    ->label(__('item.table.warehouse'))
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('item.table.status'))
                    ->getStateUsing(function ($record) {
                        return $record->status_label;
                    })
                    ->badge()
                    ->color(fn(Item $record): string => $record->status_color),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('item.table.is_active'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('item.table.created_at'))
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_profile_id')
                    ->label(__('item.filters.product_profile'))
                    ->relationship('productProfile', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('warehouse_id')
                    ->label(__('item.filters.warehouse'))
                    ->relationship('warehouse', 'title')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->label(__('item.filters.status'))
                    ->options(__('item.status_options')),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('item.filters.is_active'))
                    ->boolean()
                    ->trueLabel(__('item.filters.active'))
                    ->falseLabel(__('item.filters.inactive'))
                    ->native(false),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make()
                    ->label('مشاهده')
                    ->icon('heroicon-o-eye'),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'view' => Pages\ViewItem::route('/{record}'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
