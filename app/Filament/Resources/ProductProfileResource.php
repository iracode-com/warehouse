<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductProfileResource\Pages;
use App\Models\ProductProfile;
use App\Models\Category;
use App\Models\Warehouse;
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
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ProductProfileResource extends Resource
{
    protected static ?string $model = ProductProfile::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-identification';
    }

    public static function getNavigationGroup(): ?string
    {
        return __('product-profile.navigation.group');
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationLabel(): string
    {
        return __('product-profile.navigation.singular');
    }

    public static function getModelLabel(): string
    {
        return __('product-profile.navigation.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('product-profile.navigation.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make(__('product-profile.sections.basic_info'))
                    ->description(__('product-profile.sections.basic_info_desc'))
                    ->icon('heroicon-o-identification')
                    ->iconColor('primary')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('product-profile.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->datalist(function () {
                                return ProductProfile::pluck('name')->unique()->toArray();
                            })
                            ->helperText(__('product-profile.fields.name_helper')),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label(__('product-profile.fields.category_id'))
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($set, $state) {
                                        if ($state) {
                                            $sku = ProductProfile::generateSKU($state);
                                            $set('sku', $sku);
                                            $set('barcode', ProductProfile::generateBarcode($sku));
                                            $set('qr_code', ProductProfile::generateQRCode($sku));
                                        }
                                    }),

                                Forms\Components\Select::make('packaging_type_id')
                                    ->label(__('product-profile.fields.packaging_type'))
                                    ->required()
                                    ->relationship('packagingType', 'name')
                                    ->searchable()
                                    ->preload(),

                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('sku')
                                    ->label(__('product-profile.fields.sku'))
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText(__('product-profile.fields.sku_helper')),

                                Forms\Components\TextInput::make('barcode')
                                    ->label(__('product-profile.fields.barcode'))
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText(__('product-profile.fields.barcode_helper')),

                                Forms\Components\TextInput::make('qr_code')
                                    ->label(__('product-profile.fields.qr_code'))
                                    ->maxLength(255)
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText(__('product-profile.fields.qr_code_helper')),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('product_type')
                                    ->label(__('product-profile.fields.product_type'))
                                    ->required()
                                    ->options(__('product-profile.options.product_types')),

                                Forms\Components\Select::make('brand_id')
                                    ->label(__('product-profile.fields.brand'))
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\TextInput::make('model')
                                    ->label(__('product-profile.fields.model'))
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->label(__('product-profile.fields.description'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),


                Section::make(__('product-profile.sections.units_pricing'))
                    ->description(__('product-profile.sections.units_pricing_desc'))
                    ->icon('heroicon-o-scale')
                    ->iconColor('success')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // Forms\Components\Select::make('unit_of_measure')
                                //     ->label(__('product-profile.fields.unit_of_measure'))
                                //     ->options(__('product-profile.options.units'))
                                //     ->searchable(),

                                Forms\Components\Select::make('primary_unit')
                                    ->label(__('product-profile.fields.primary_unit'))
                                    ->required()
                                    ->options(__('product-profile.options.units'))
                                    ->searchable(),

                                Forms\Components\Select::make('secondary_unit')
                                    ->label(__('product-profile.fields.secondary_unit'))
                                    ->required()
                                    ->options(__('product-profile.options.units'))
                                    ->searchable(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('pricing_method')
                                    ->label(__('product-profile.fields.pricing_method'))
                                    ->options(__('product-profile.options.pricing_methods')),

                                Forms\Components\TextInput::make('standard_cost')
                                    ->label(__('product-profile.fields.standard_cost'))
                                    ->numeric()
                                    ->required()
                                    ->prefix('ریال'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.physical_specs'))
                    ->description(__('product-profile.sections.physical_specs_desc'))
                    ->icon('heroicon-o-cube')
                    ->iconColor('warning')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('weight')
                                    ->label(__('product-profile.fields.weight'))
                                    ->numeric()
                                    ->step(0.01)
                                    ->suffix('کیلوگرم'),

                                Forms\Components\TextInput::make('length')
                                    ->label(__('product-profile.fields.length'))
                                    ->numeric()
                                    ->step(0.01)
                                    ->suffix('سانتی‌متر'),

                                Forms\Components\TextInput::make('width')
                                    ->label(__('product-profile.fields.width'))
                                    ->numeric()
                                    ->step(0.01)
                                    ->suffix('سانتی‌متر'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('height')
                                    ->label(__('product-profile.fields.height'))
                                    ->numeric()
                                    ->step(0.01)
                                    ->suffix('سانتی‌متر'),

                                Forms\Components\TextInput::make('volume')
                                    ->label(__('product-profile.fields.volume'))
                                    ->numeric()
                                    ->step(0.01)
                                    ->suffix('لیتر'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.features_status'))
                    ->description(__('product-profile.sections.features_status_desc'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->iconColor('info')
                    ->columnSpanFull()
                    ->schema([
                        // Grid::make(2)
                        //     ->schema([
                        //         Forms\Components\TextInput::make('feature_1')
                        //             ->label(__('product-profile.fields.feature_1'))
                        //             ->maxLength(255)
                        //             ->helperText(__('product-profile.fields.feature_1_helper')),

                        //         Forms\Components\TextInput::make('feature_2')
                        //             ->label(__('product-profile.fields.feature_2'))
                        //             ->maxLength(255)
                        //             ->helperText(__('product-profile.fields.feature_2_helper')),
                        //     ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('has_expiry_date')
                                    ->label(__('product-profile.fields.has_expiry_date')),
                                Forms\Components\Toggle::make('is_flammable')
                                    ->label(__('product-profile.fields.is_flammable')),
                                Forms\Components\Toggle::make('has_return_policy')
                                    ->label(__('product-profile.fields.has_return_policy')),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('consumption_status')
                                    ->label(__('product-profile.fields.consumption_status'))
                                    ->options(__('product-profile.options.consumption_statuses')),

                                Forms\Components\TextInput::make('product_address')
                                    ->label(__('product-profile.fields.product_address'))
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.inventory_reorder'))
                    ->description(__('product-profile.sections.inventory_reorder_desc'))
                    ->icon('heroicon-o-chart-bar')
                    ->iconColor('secondary')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        // Forms\Components\KeyValue::make('minimum_stock_by_location')
                        //     ->label(__('product-profile.fields.minimum_stock_by_location'))
                        //     ->keyLabel('مکان (استان/شهرستان)')
                        //     ->valueLabel('حداقل موجودی'),

                        // Forms\Components\KeyValue::make('reorder_point_by_location')
                        //     ->label(__('product-profile.fields.reorder_point_by_location'))
                        //     ->keyLabel('مکان (استان/شهرستان)')
                        //     ->valueLabel('نقطه سفارش'),

                        Forms\Components\TextInput::make('minimum_stock_by_location')
                            ->label(__('product-profile.fields.minimum_stock_by_location'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('reorder_point_by_location')
                            ->label(__('product-profile.fields.reorder_point_by_location'))
                            ->numeric()
                            ->required(),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.technical_storage'))
                    ->description(__('product-profile.sections.technical_storage_desc'))
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->iconColor('gray')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('has_technical_specs')
                                    ->label(__('product-profile.fields.has_technical_specs'))
                                    ->default(false)
                                    ->live(),

                                Forms\Components\Toggle::make('has_storage_conditions')
                                    ->label(__('product-profile.fields.has_storage_conditions'))
                                    ->default(false)
                                    ->live(),

                                Forms\Components\Toggle::make('has_inspection')
                                    ->label(__('product-profile.fields.has_inspection'))
                                    ->default(false)
                                    ->live(),
                            ]),

                        Forms\Components\Textarea::make('technical_specs')
                            ->label(__('product-profile.fields.technical_specs'))
                            ->rows(4)
                            ->visible(fn($get): bool => $get('has_technical_specs'))
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('storage_conditions')
                            ->label(__('product-profile.fields.storage_conditions'))
                            ->rows(4)
                            ->visible(fn($get): bool => $get('has_storage_conditions'))
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('inspection_details')
                            ->label(__('product-profile.fields.inspection_details'))
                            ->rows(4)
                            ->visible(fn($get): bool => $get('has_inspection'))
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.similar_valuation'))
                    ->description(__('product-profile.sections.similar_valuation_desc'))
                    ->icon('heroicon-o-currency-dollar')
                    ->iconColor('success')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Toggle::make('has_similar_products')
                            ->label(__('product-profile.fields.has_similar_products'))
                            ->default(false)
                            ->live(),

                        Forms\Components\Select::make('similar_products')
                            ->label(__('product-profile.fields.similar_products'))
                            ->multiple()
                            ->options(function () {
                                return ProductProfile::where('is_active', true)->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->visible(fn($get): bool => $get('has_similar_products'))
                            ->columnSpanFull(),

                        // Grid::make(2)
                        //     ->schema([
                        //         Forms\Components\TextInput::make('estimated_value')
                        //             ->label(__('product-profile.fields.estimated_value'))
                        //             ->numeric()
                        //             ->step(0.01)
                        //             ->prefix('ریال'),

                        //         Forms\Components\TextInput::make('annual_inflation_rate')
                        //             ->label(__('product-profile.fields.annual_inflation_rate'))
                        //             ->numeric()
                        //             ->step(0.01)
                        //             ->suffix('%')
                        //             ->helperText(__('product-profile.fields.annual_inflation_rate_helper')),
                        //     ]),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.related_warehouses'))
                    ->description(__('product-profile.sections.related_warehouses_desc'))
                    ->icon('heroicon-o-building-storefront')
                    ->iconColor('warning')
                    ->columnSpanFull()
                    ->visible(false)
                    ->schema([
                        Forms\Components\Select::make('related_warehouses')
                            ->label(__('product-profile.fields.related_warehouses'))
                            ->multiple()
                            ->options(__('product-profile.options.warehouse_selection'))
                            ->helperText(__('product-profile.fields.related_warehouses_helper')),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.production_specs'))
                    ->description(__('product-profile.sections.production_specs_desc'))
                    ->icon('heroicon-o-building-office-2')
                    ->iconColor('info')
                    ->columnSpanFull()
                    ->visible(false)
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('manufacturer')
                                    ->label(__('product-profile.fields.manufacturer'))
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('country_of_origin')
                                    ->label(__('product-profile.fields.country_of_origin'))
                                    ->maxLength(255),
                            ]),

                        Forms\Components\TextInput::make('shelf_life_days')
                            ->label(__('product-profile.fields.shelf_life_days'))
                            ->numeric()
                            ->minValue(0)
                            ->suffix('روز'),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.status_settings'))
                    ->description(__('product-profile.sections.status_settings_desc'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->iconColor('gray')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label(__('product-profile.fields.status'))
                                    ->options(__('product-profile.options.statuses'))
                                    ->required()
                                    ->default(ProductProfile::STATUS_ACTIVE),

                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('product-profile.fields.is_active'))
                                    ->default(true),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.files_images'))
                    ->description(__('product-profile.sections.files_images_desc'))
                    ->icon('heroicon-o-photo')
                    ->iconColor('secondary')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label(__('product-profile.fields.images'))
                            ->image()
                            ->multiple()
                            ->directory('product-profiles/images')
                            ->imageEditor()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('documents')
                            ->label(__('product-profile.fields.documents'))
                            ->multiple()
                            ->directory('product-profiles/documents')
                            ->downloadable()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('product-profile.sections.descriptions'))
                    ->description(__('product-profile.sections.descriptions_desc'))
                    ->icon('heroicon-o-document-text')
                    ->iconColor('gray')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label(__('product-profile.fields.notes'))
                            ->rows(3),

                        Forms\Components\Textarea::make('additional_description')
                            ->label(__('product-profile.fields.additional_description'))
                            ->rows(4),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sku')
                    ->label(__('product-profile.table.sku'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('product-profile.table.name'))
                    ->searchable()
                    ->sortable()
                    ->description(fn(ProductProfile $record): string => $record->brand ? "برند: {$record->brand}" : ''),

                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('product-profile.table.category'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('category.category_type')
                    ->label(__('product-profile.table.category_type'))
                    ->getStateUsing(function ($record) {
                        return $record->category && $record->category->category_type ?
                            __('product-profile.options.category_types.' . $record->category->category_type) : '';
                    })
                    ->badge()
                    ->color('warning')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('packagingType.name')
                    ->label(__('product-profile.fields.packaging_type'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->toggleable(),


                Tables\Columns\TextColumn::make('product_type')
                    ->label(__('product-profile.table.product_type'))
                    ->getStateUsing(function ($record) {
                        return $record->product_type ? __('product-profile.options.product_types.' . $record->product_type) : '';
                    })
                    ->badge()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->label(__('product-profile.table.brand'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('consumption_status')
                    ->label(__('product-profile.table.consumption_status'))
                    ->getStateUsing(function ($record) {
                        return $record->consumption_status ? __('product-profile.options.consumption_statuses.' . $record->consumption_status) : '';
                    })
                    ->badge()
                    ->color(fn(ProductProfile $record): string => match ($record->consumption_status) {
                        'high_consumption' => 'success',
                        'strategic' => 'warning',
                        'low_consumption' => 'info',
                        'stagnant' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('estimated_value')
                    ->label(__('product-profile.table.estimated_value'))
                    ->numeric()
                    ->sortable()
                    ->money('IRR')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('has_expiry_date')
                    ->label(__('product-profile.table.has_expiry_date'))
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_flammable')
                    ->label(__('product-profile.table.is_flammable'))
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('product-profile.table.status'))
                    ->getStateUsing(function ($record) {
                        return $record->status_label;
                    })
                    ->badge()
                    ->color(fn(ProductProfile $record): string => $record->status_color),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('product-profile.table.is_active'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('product-profile.table.created_at'))
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label(__('product-profile.filters.category'))
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('category.category_type')
                    ->label(__('product-profile.filters.category_type'))
                    ->options(__('product-profile.options.category_types')),

                Tables\Filters\SelectFilter::make('packaging_type_id')
                    ->label(__('product-profile.filters.packaging_type'))
                    ->relationship('packagingType', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('brand_id')
                    ->label(__('product-profile.filters.brand'))
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload(),


                Tables\Filters\SelectFilter::make('product_type')
                    ->label(__('product-profile.filters.product_type'))
                    ->options(__('product-profile.options.product_types')),

                Tables\Filters\SelectFilter::make('consumption_status')
                    ->label(__('product-profile.filters.consumption_status'))
                    ->options(__('product-profile.options.consumption_statuses')),

                Tables\Filters\SelectFilter::make('status')
                    ->label(__('product-profile.filters.status'))
                    ->options(__('product-profile.options.statuses')),

                Tables\Filters\TernaryFilter::make('has_expiry_date')
                    ->label(__('product-profile.filters.has_expiry_date'))
                    ->boolean()
                    ->trueLabel(__('product-profile.filters.has'))
                    ->falseLabel(__('product-profile.filters.has_not'))
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_flammable')
                    ->label(__('product-profile.filters.is_flammable'))
                    ->boolean()
                    ->trueLabel(__('product-profile.filters.has'))
                    ->falseLabel(__('product-profile.filters.has_not'))
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('product-profile.filters.is_active'))
                    ->boolean()
                    ->trueLabel(__('product-profile.filters.active'))
                    ->falseLabel(__('product-profile.filters.inactive'))
                    ->native(false),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make()
                    ->label(__('product-profile.actions.view'))
                    ->icon('heroicon-o-eye'),
                Action::make('copy')
                    ->label(__('product-profile.actions.copy'))
                    ->icon('heroicon-o-document-duplicate')
                    ->color('info')
                    ->form([
                        Forms\Components\TextInput::make('new_name')
                            ->label(__('product-profile.copy_form.new_name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('new_sku')
                            ->label(__('product-profile.copy_form.new_sku'))
                            ->maxLength(255)
                            ->helperText(__('product-profile.copy_form.new_sku_helper')),
                    ])
                    ->action(function (ProductProfile $record, array $data): void {
                        $newProduct = $record->copyProduct($data['new_name'], $data['new_sku'] ?: null);

                        Notification::make()
                            ->title(__('product-profile.actions.copy_success'))
                            ->body(__('product-profile.actions.copy_success_body', ['sku' => $newProduct->sku]))
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListProductProfiles::route('/'),
            'create' => Pages\CreateProductProfile::route('/create'),
            'view' => Pages\ViewProductProfile::route('/{record}'),
            'edit' => Pages\EditProductProfile::route('/{record}/edit'),
        ];
    }
}