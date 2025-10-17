<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BaseResource\Pages;
use App\Models\Base;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class BaseResource extends Resource
{
    protected static ?string $model = Base::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-building-office-2';
    }

    public static function getNavigationLabel(): string
    {
        return __('base.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('base.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('base.model_label_plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('base.navigation_group');
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('base.basic_info'))
                    ->description(__('base.basic_info_desc'))
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('primary')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('region_id')
                            ->label(__('base.region_id'))
                            ->relationship('region', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpan(1),

                        Forms\Components\Select::make('type_operational_center')
                            ->label(__('base.type_operational_center'))
                            ->options(__('base.type_operational_center_options'))
                            ->required()
                            ->native(false)
                            ->columnSpan(1),

                        Forms\Components\Select::make('account_type')
                            ->label(__('base.account_type'))
                            ->options(__('base.account_type_options'))
                            ->native(false)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('coding')
                            ->label(__('base.coding'))
                            ->maxLength(11)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('coding_old')
                            ->label(__('base.coding_old'))
                            ->maxLength(11)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('three_digit_code')
                            ->label(__('base.three_digit_code'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(999)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('three_digit_code_new')
                            ->label(__('base.three_digit_code_new'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(999)
                            ->columnSpan(1),

                        Forms\Components\Select::make('type_ownership')
                            ->label(__('base.type_ownership'))
                            ->options(__('base.type_ownership_options'))
                            ->native(false)
                            ->columnSpan(1),

                        Forms\Components\Select::make('type_structure')
                            ->label(__('base.type_structure'))
                            ->options(__('base.type_structure_options'))
                            ->native(false)
                            ->columnSpan(1),

                        Forms\Components\Select::make('seasonal_type')
                            ->label(__('base.seasonal_type'))
                            ->options(__('base.seasonal_type_options'))
                            ->native(false)
                            ->columnSpan(1),

                        Forms\Components\Select::make('license_state')
                            ->label(__('base.license_state'))
                            ->options(__('base.license_state_options'))
                            ->native(false)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('branch_type')
                            ->label(__('base.branch_type'))
                            ->maxLength(10)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('memory_martyr')
                            ->label(__('base.memory_martyr'))
                            ->maxLength(50)
                            ->columnSpan(1),

                        Forms\Components\DatePicker::make('start_activity')
                            ->label(__('base.start_activity'))
                            ->jalali()
                            ->columnSpan(1),

                        Forms\Components\DatePicker::make('end_activity')
                            ->label(__('base.end_activity'))
                            ->jalali()
                            ->columnSpan(1),

                        Forms\Components\CheckboxList::make('activity_days')
                            ->label(__('base.activity_days'))
                            ->options(__('base.activity_days_options'))
                            ->columns(4)
                            ->columnSpanFull(),

                        Forms\Components\DatePicker::make('date_activity_days')
                            ->label(__('base.date_activity_days'))
                            ->jalali()
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label(__('base.is_active'))
                            ->default(true)
                            ->columnSpan(1),
                    ]),

                Section::make(__('base.contact_info'))
                    ->description(__('base.contact_info_desc'))
                    ->icon('heroicon-o-phone')
                    ->iconColor('success')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label(__('base.phone'))
                            ->tel()
                            ->maxLength(11)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('fixed_number')
                            ->label(__('base.fixed_number'))
                            ->tel()
                            ->maxLength(11)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('mobile')
                            ->label(__('base.mobile'))
                            ->tel()
                            ->maxLength(11)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('fax')
                            ->label(__('base.fax'))
                            ->tel()
                            ->maxLength(11)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('satellite_phone')
                            ->label(__('base.satellite_phone'))
                            ->tel()
                            ->maxLength(11)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('postal_code')
                            ->label(__('base.postal_code'))
                            ->maxLength(10)
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('address')
                            ->label(__('base.address'))
                            ->rows(3)
                            ->maxLength(150)
                            ->columnSpanFull(),
                    ]),

                Section::make(__('base.location_info'))
                    ->description(__('base.location_info_desc'))
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('warning')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('lat')
                            ->label(__('base.lat'))
                            ->numeric()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('lon')
                            ->label(__('base.lon'))
                            ->numeric()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('height')
                            ->label(__('base.height'))
                            ->numeric()
                            ->suffix('متر')
                            ->columnSpan(1),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('lat_deg')
                                    ->label(__('base.lat_deg'))
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('lat_min')
                                    ->label(__('base.lat_min'))
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('lat_sec')
                                    ->label(__('base.lat_sec'))
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),
                            ])
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('lon_deg')
                                    ->label(__('base.lon_deg'))
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('lon_min')
                                    ->label(__('base.lon_min'))
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('lon_sec')
                                    ->label(__('base.lon_sec'))
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),
                            ])
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('arena')
                            ->label(__('base.arena'))
                            ->maxLength(10)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('ayan')
                            ->label(__('base.ayan'))
                            ->maxLength(10)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('distance_to_branch')
                            ->label(__('base.distance_to_branch'))
                            ->numeric()
                            ->suffix('کیلومتر')
                            ->default(0)
                            ->columnSpan(1),
                    ]),

                Section::make(__('base.status_info'))
                    ->description(__('base.status_info_desc'))
                    ->icon('heroicon-o-signal')
                    ->iconColor('danger')
                    ->columns(4)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Toggle::make('status_emis')
                            ->label(__('base.status_emis'))
                            ->default(true)
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('status_equipment')
                            ->label(__('base.status_equipment'))
                            ->default(true)
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('status_dims')
                            ->label(__('base.status_dims'))
                            ->default(true)
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('status_air_relief')
                            ->label(__('base.status_air_relief'))
                            ->default(true)
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('status_memberrcs')
                            ->label(__('base.status_memberrcs'))
                            ->default(true)
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('status_emdadyar')
                            ->label(__('base.status_emdadyar'))
                            ->default(true)
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('status_webgis')
                            ->label(__('base.status_webgis'))
                            ->default(true)
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('not_conditions')
                            ->label(__('base.not_conditions'))
                            ->default(false)
                            ->columnSpan(1),
                    ]),

                Section::make(__('base.additional_info'))
                    ->description(__('base.additional_info_desc'))
                    ->icon('heroicon-o-document-text')
                    ->iconColor('info')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('place_payment')
                            ->label(__('base.place_payment'))
                            ->options(__('base.place_payment_options'))
                            ->default(0)
                            ->native(false)
                            ->columnSpan(1),

                        Forms\Components\Select::make('type_personnel_emis')
                            ->label(__('base.type_personnel_emis'))
                            ->options(__('base.type_personnel_emis_options'))
                            ->default(0)
                            ->native(false)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('raromis_id')
                            ->label(__('base.raromis_id'))
                            ->numeric()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('member_id')
                            ->label(__('base.member_id'))
                            ->numeric()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('emdadyar_id')
                            ->label(__('base.emdadyar_id'))
                            ->numeric()
                            ->columnSpan(1),

                        Forms\Components\FileUpload::make('img_header')
                            ->label(__('base.img_header'))
                            ->image()
                            ->directory('bases/headers')
                            ->columnSpan(1),

                        Forms\Components\FileUpload::make('img_license')
                            ->label(__('base.img_license'))
                            ->image()
                            ->directory('bases/licenses')
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('description')
                            ->label(__('base.description'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('region.name')
                    ->label(__('base.table.region'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('display_name')
                    ->label(__('base.table.name'))
                    ->searchable(query: function ($query, $search) {
                        return $query->where('name->fa', 'like', "%{$search}%")
                            ->orWhere('name->en', 'like', "%{$search}%");
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('coding')
                    ->label(__('base.table.coding'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type_operational_center')
                    ->label(__('base.table.type'))
                    ->formatStateUsing(fn ($state) => __('base.type_operational_center_options')[$state] ?? $state)
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        1 => 'success',
                        2 => 'warning',
                        3 => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('phone')
                    ->label(__('base.table.phone'))
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('base.table.is_active'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('region_id')
                    ->label(__('base.region_id'))
                    ->relationship('region', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('type_operational_center')
                    ->label(__('base.type_operational_center'))
                    ->options(__('base.type_operational_center_options')),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('base.is_active')),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBases::route('/'),
            'create' => Pages\CreateBase::route('/create'),
            'view' => Pages\ViewBase::route('/{record}'),
            'edit' => Pages\EditBase::route('/{record}/edit'),
        ];
    }
}
