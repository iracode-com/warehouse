<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegionResource\Pages;
use App\Models\Location\Region;
use App\Enums\RegionType;
use Filament\Forms;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\Tab as FilterTab;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use BackedEnum;

class RegionResource extends Resource
{
    protected static ?string $model = Region::class;


    protected static ?string $navigationLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('warehouse.region.navigation.plural');
    }

    public static function getModelLabel(): string
    {
        return __('warehouse.region.navigation.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('warehouse.region.navigation.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('base.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Tabs::make('region_tabs')
                    ->tabs([
                        // Basic Information Tab
                        Tab::make(__('warehouse.region.basic_info'))
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Section::make(__('warehouse.region.sections.basic_info'))
                                    ->description(__('warehouse.region.sections.basic_info_desc'))
                                    ->icon('heroicon-o-map-pin')
                                    ->iconColor('primary')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label(__('warehouse.region.name'))
                                                    ->required()
                                                    ->maxLength(255),
                                                
                                                Forms\Components\Select::make('type')
                                                    ->label(__('warehouse.region.type'))
                                                    ->options(RegionType::class)
                                                    ->required()
                                                    ->searchable(),
                                            ]),
                                        
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('code')
                                                    ->label(__('warehouse.region.code'))
                                                    ->maxLength(255),
                                                
                                                Forms\Components\Select::make('parent_id')
                                                    ->label(__('warehouse.region.parent'))
                                                    ->relationship('parent', 'name')
                                                    ->searchable()
                                                    ->preload(),
                                            ]),
                                        
                                        Forms\Components\Textarea::make('description')
                                            ->label(__('warehouse.region.description'))
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),

                                Section::make(__('warehouse.region.sections.status_info'))
                                    ->description(__('warehouse.region.sections.status_info_desc'))
                                    ->icon('heroicon-o-check-circle')
                                    ->iconColor('success')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\Toggle::make('is_active')
                                                    ->label(__('warehouse.region.is_active'))
                                                    ->default(true),
                                                
                                                Forms\Components\TextInput::make('ordering')
                                                    ->label(__('warehouse.region.ordering'))
                                                    ->numeric()
                                                    ->default(0),
                                            ]),
                                    ])
                                    ->collapsible(),
                            ]),

                        // Geographic Information Tab
                        Tab::make(__('warehouse.region.geographic_info'))
                            ->icon('heroicon-o-globe-americas')
                            ->schema([
                                Section::make(__('warehouse.region.sections.coordinates'))
                                    ->description(__('warehouse.region.sections.coordinates_desc'))
                                    ->icon('heroicon-o-map')
                                    ->iconColor('info')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Map::make('region_location')
                                                    ->showMarker(true)
                                                    ->liveLocation(true)
                                                    ->label('موقعیت منطقه روی نقشه')
                                                    ->columnSpanFull()
                                                    ->defaultLocation(latitude: "35.7219", longitude: "51.3347")
                                                    ->afterStateUpdated(function (Set $set, ?array $state, $livewire): void {
                                                        if ($state) {
                                                            $set('lat', $state['lat']);
                                                            $set('lon', $state['lng']);
                                                        }
                                                    })
                                                    ->live()
                                                    ->reactive()
                                                    ->extraStyles([
                                                        'min-height: 50vh',
                                                        'border-radius: 50px'
                                                    ]),
                                            ]),

                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('lat')
                                                    ->label(__('warehouse.region.lat'))
                                                    ->numeric()
                                                    ->step(0.0000001)
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                        $set('region_location.lat', $state);
                                                    }),
                                                
                                                Forms\Components\TextInput::make('lon')
                                                    ->label(__('warehouse.region.lon'))
                                                    ->numeric()
                                                    ->step(0.0000001)
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                        $set('region_location.lng', $state);
                                                    }),
                                                
                                                Forms\Components\TextInput::make('height')
                                                    ->label(__('warehouse.region.height'))
                                                    ->numeric()
                                                    ->step(0.01)
                                                    ->suffix('متر'),
                                            ]),
                                    ])
                                    ->collapsible(),

                                Section::make(__('warehouse.region.sections.central_info'))
                                    ->description(__('warehouse.region.sections.central_info_desc'))
                                    ->icon('heroicon-o-building-office')
                                    ->iconColor('warning')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('central_address')
                                                    ->label(__('warehouse.region.central_address'))
                                                    ->maxLength(255),
                                                
                                                Forms\Components\TextInput::make('central_postal_code')
                                                    ->label(__('warehouse.region.central_postal_code'))
                                                    ->maxLength(255),
                                            ]),
                                        
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('central_phone')
                                                    ->label(__('warehouse.region.central_phone'))
                                                    ->tel()
                                                    ->maxLength(255),
                                                
                                                Forms\Components\TextInput::make('central_mobile')
                                                    ->label(__('warehouse.region.central_mobile'))
                                                    ->tel()
                                                    ->maxLength(255),
                                                
                                                Forms\Components\TextInput::make('central_fax')
                                                    ->label(__('warehouse.region.central_fax'))
                                                    ->tel()
                                                    ->maxLength(255),
                                            ]),
                                        
                                        Forms\Components\TextInput::make('central_email')
                                            ->label(__('warehouse.region.central_email'))
                                            ->email()
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),
                            ]),
                    ])
                    ->persistTabInQueryString()
                    ->id('region-form-tabs'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('warehouse.region.table.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('type')
                    ->label(__('warehouse.region.table.type'))
                    ->badge()
                    ->color(fn (RegionType $state): string => match($state) {
                        RegionType::COUNTRY => 'primary',
                        RegionType::HEADQUARTER => 'success',
                        RegionType::PROVINCE => 'info',
                        RegionType::BRANCH => 'warning',
                        RegionType::TOWN => 'secondary',
                        RegionType::DISTRICT => 'gray',
                        RegionType::RURAL_DISTRICT => 'danger',
                        RegionType::CITY => 'success',
                        RegionType::VILLAGE => 'info',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('parent.name')
                    ->label(__('warehouse.region.table.parent'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('code')
                    ->label(__('warehouse.region.table.code'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('warehouse.region.table.is_active'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('lat')
                    ->label(__('warehouse.region.table.lat'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('lon')
                    ->label(__('warehouse.region.table.lon'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('warehouse.region.table.created_at'))
                    ->dateTime('j F Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('warehouse.region.filters.type'))
                    ->options(RegionType::class)
                    ->multiple(),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('warehouse.region.filters.is_active'))
                    ->placeholder(__('warehouse.region.filters.all'))
                    ->trueLabel(__('warehouse.region.filters.active'))
                    ->falseLabel(__('warehouse.region.filters.inactive')),
                
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label(__('warehouse.region.filters.parent'))
                    ->relationship('parent', 'name')
                    ->searchable(),
            ])
            ->actions([
                ViewAction::make()
                    ->label(__('warehouse.region.actions.view'))
                    ->icon('heroicon-o-eye'),
                EditAction::make()
                    ->label(__('warehouse.region.actions.edit'))
                    ->icon('heroicon-o-pencil'),
                DeleteAction::make()
                    ->label(__('warehouse.region.actions.delete'))
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('warehouse.region.actions.delete_selected'))
                        ->requiresConfirmation(),
                ])
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
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
            'index' => Pages\ListRegions::route('/'),
            'create' => Pages\CreateRegion::route('/create'),
            'view' => Pages\ViewRegion::route('/{record}'),
            'edit' => Pages\EditRegion::route('/{record}/edit'),
        ];
    }
}
