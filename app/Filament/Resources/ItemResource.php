<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use App\Models\ProductProfile;
use App\Models\Warehouse;
use App\Models\Location\Zone;
use App\Models\Location\Rack;
use App\Models\Location\ShelfLevel;
use App\Models\Location\Pallet;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

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
                            ->getOptionLabelFromRecordUsing(fn (ProductProfile $record): string => "{$record->name} ({$record->sku})")
                            ->helperText(__('item.fields.product_profile_id_helper')),
                    ]),

                Section::make(__('item.sections.item_info'))
                    ->description(__('item.sections.item_info_desc'))
                    ->icon('heroicon-o-cube')
                    ->iconColor('success')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('serial_number')
                                    ->label(__('item.fields.serial_number'))
                                    ->maxLength(255)
                                    ->helperText(__('item.fields.serial_number_helper')),

                                Forms\Components\Select::make('status')
                                    ->label(__('item.fields.status'))
                                    ->options(__('item.status_options'))
                                    ->default(Item::STATUS_ACTIVE)
                                    ->required()
                                    ->helperText(__('item.fields.status_helper')),
                            ]),
                    ]),

                Section::make(__('item.sections.inventory_pricing'))
                    ->description(__('item.sections.inventory_pricing_desc'))
                    ->icon('heroicon-o-currency-dollar')
                    ->iconColor('warning')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('current_stock')
                                    ->label(__('item.fields.current_stock'))
                                    ->numeric()
                                    ->default(0)
                                    ->helperText(__('item.fields.current_stock_helper')),

                                Forms\Components\TextInput::make('min_stock')
                                    ->label(__('item.fields.min_stock'))
                                    ->numeric()
                                    ->default(0)
                                    ->helperText(__('item.fields.min_stock_helper')),

                                Forms\Components\TextInput::make('max_stock')
                                    ->label(__('item.fields.max_stock'))
                                    ->numeric()
                                    ->helperText(__('item.fields.max_stock_helper')),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('unit_cost')
                                    ->label(__('item.fields.unit_cost'))
                                    ->numeric()
                                    ->step(0.01)
                                    ->prefix('ریال')
                                    ->helperText(__('item.fields.unit_cost_helper')),

                                Forms\Components\TextInput::make('selling_price')
                                    ->label(__('item.fields.selling_price'))
                                    ->numeric()
                                    ->step(0.01)
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
                                    ->jalali(),

                                Forms\Components\DatePicker::make('expiry_date')
                                    ->label(__('item.fields.expiry_date'))
                                    ->helperText(__('item.fields.expiry_date_helper'))
                                    ->jalali(),

                                Forms\Components\DatePicker::make('purchase_date')
                                    ->label(__('item.fields.purchase_date'))
                                    ->helperText(__('item.fields.purchase_date_helper'))
                                    ->jalali(),
                            ]),
                    ]),

                Section::make(__('item.sections.location'))
                    ->description(__('item.sections.location_desc'))
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('secondary')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('warehouse_id')
                                    ->label(__('item.fields.warehouse_id'))
                                    ->relationship('warehouse', 'title')
                                    ->searchable()
                                    ->preload()
                                    ->helperText(__('item.fields.warehouse_id_helper')),

                                Forms\Components\Select::make('zone_id')
                                    ->label(__('item.fields.zone_id'))
                                    ->relationship('zone', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText(__('item.fields.zone_id_helper')),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('rack_id')
                                    ->label(__('item.fields.rack_id'))
                                    ->relationship('rack', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText(__('item.fields.rack_id_helper')),

                                Forms\Components\Select::make('shelf_level_id')
                                    ->label(__('item.fields.shelf_level_id'))
                                    ->relationship('shelfLevel', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText(__('item.fields.shelf_level_id_helper')),

                                Forms\Components\Select::make('pallet_id')
                                    ->label(__('item.fields.pallet_id'))
                                    ->relationship('pallet', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText(__('item.fields.pallet_id_helper')),
                            ]),
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
                    ->label(__('item.table.name'))
                    ->searchable()
                    ->sortable()
                    ->description(fn (Item $record): string => $record->productProfile?->brand ? "برند: {$record->productProfile->brand}" : ''),

                Tables\Columns\TextColumn::make('serial_number')
                    ->label(__('item.table.serial_number'))
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('current_stock')
                    ->label(__('item.table.current_stock'))
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (Item $record): string => $record->stock_status_color),

                Tables\Columns\TextColumn::make('unit_cost')
                    ->label(__('item.table.unit_cost'))
                    ->numeric()
                    ->sortable()
                    ->money('IRR')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('selling_price')
                    ->label(__('item.table.selling_price'))
                    ->numeric()
                    ->sortable()
                    ->money('IRR')
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
                    ->color(fn (Item $record): string => $record->status_color),

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
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}