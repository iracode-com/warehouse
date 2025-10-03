<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Models\Branch;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use BackedEnum;
use Dotswan\MapPicker\Fields\Map;
use Filament\Schemas\Components\Utilities\Set;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;


    protected static ?string $navigationLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('warehouse.branch.navigation.plural');
    }

    public static function getModelLabel(): string
    {
        return __('warehouse.branch.navigation.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('warehouse.branch.navigation.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('warehouse.navigation_groups.location_management');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Tabs::make('branch_tabs')
                    ->tabs([
                        // Basic Information Tab
                        Tab::make(__('warehouse.branch_tabs.basic_info'))
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                Section::make(__('warehouse.branch.sections.basic_info'))
                                    ->description(__('warehouse.branch.sections.basic_info_desc'))
                                    ->icon('heroicon-o-building-office')
                                    ->iconColor('primary')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label(__('warehouse.branch.name'))
                                                    ->required()
                                                    ->maxLength(255),
                                                
                                                Forms\Components\Select::make('branch_type')
                                                    ->label(__('warehouse.branch.branch_type'))
                                                    ->options([
                                                        Branch::BRANCH_TYPE_COUNTY => 'شهرستان',
                                                        Branch::BRANCH_TYPE_HEADQUARTERS => 'ستاد',
                                                        Branch::BRANCH_TYPE_BRANCH => 'شعبه',
                                                        Branch::BRANCH_TYPE_INDEPENDENT_OFFICE => 'دفترنمایندگی مستقل',
                                                        Branch::BRANCH_TYPE_DEPENDENT_OFFICE => 'دفترنمایندگی وابسته',
                                                        Branch::BRANCH_TYPE_URBAN_AREA => 'مناطق شهری',
                                                    ])
                                                    ->required()
                                                    ->searchable(),
                                            ]),
                                        
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('two_digit_code')
                                                    ->label(__('warehouse.branch.two_digit_code'))
                                                    ->maxLength(2),
                                                
                                                Forms\Components\TextInput::make('date_establishment')
                                                    ->label(__('warehouse.branch.date_establishment'))
                                                    ->maxLength(10),
                                                
                                                Forms\Components\TextInput::make('coding')
                                                    ->label(__('warehouse.branch.coding'))
                                                    ->maxLength(6),
                                            ]),
                                        
                                        Forms\Components\Textarea::make('description')
                                            ->label(__('warehouse.branch.description'))
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),

                                Section::make(__('warehouse.branch.sections.contact_info'))
                                    ->description(__('warehouse.branch.sections.contact_info_desc'))
                                    ->icon('heroicon-o-phone')
                                    ->iconColor('success')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('phone')
                                                    ->label(__('warehouse.branch.phone'))
                                                    ->tel()
                                                    ->maxLength(11),
                                                
                                                Forms\Components\TextInput::make('fax')
                                                    ->label(__('warehouse.branch.fax'))
                                                    ->tel()
                                                    ->maxLength(11),
                                                
                                                Forms\Components\TextInput::make('postal_code')
                                                    ->label(__('warehouse.branch.postal_code'))
                                                    ->maxLength(10),
                                            ]),
                                        
                                        Forms\Components\Textarea::make('address')
                                            ->label(__('warehouse.branch.address'))
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),
                            ]),

                        // Technical Information Tab
                        Tab::make(__('warehouse.branch_tabs.technical_info'))
                            ->icon('heroicon-o-wrench-screwdriver')
                            ->schema([
                                Section::make(__('warehouse.branch.sections.communication_info'))
                                    ->description(__('warehouse.branch.sections.communication_info_desc'))
                                    ->icon('heroicon-o-signal')
                                    ->iconColor('warning')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('vhf_address')
                                                    ->label(__('warehouse.branch.vhf_address'))
                                                    ->maxLength(20),
                                                
                                                Forms\Components\TextInput::make('hf_address')
                                                    ->label(__('warehouse.branch.hf_address'))
                                                    ->maxLength(20),
                                                
                                                Forms\Components\TextInput::make('vhf_channel')
                                                    ->label(__('warehouse.branch.vhf_channel'))
                                                    ->maxLength(20),
                                            ]),
                                    ])
                                    ->collapsible(),

                                Section::make(__('warehouse.branch.sections.geographic_info'))
                                    ->description(__('warehouse.branch.sections.geographic_info_desc'))
                                    ->icon('heroicon-o-map-pin')
                                    ->iconColor('info')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Map::make('branch_location')
                                                    ->showMarker(true)
                                                    ->liveLocation(true)
                                                    ->label('موقعیت شعبه روی نقشه')
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
                                        
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('lat')
                                                    ->label(__('warehouse.branch.lat'))
                                                    ->numeric()
                                                    ->step(0.0000001)
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                        $set('branch_location.lat', $state);
                                                    }),
                                                
                                                Forms\Components\TextInput::make('lon')
                                                    ->label(__('warehouse.branch.lon'))
                                                    ->numeric()
                                                    ->step(0.0000001)
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                        $set('branch_location.lng', $state);
                                                    }),
                                                Forms\Components\TextInput::make('lat_deg')
                                                    ->label(__('warehouse.branch.lat_deg'))
                                                    ->numeric(),
                                                
                                                Forms\Components\TextInput::make('lat_min')
                                                    ->label(__('warehouse.branch.lat_min'))
                                                    ->numeric(),
                                                
                                                Forms\Components\TextInput::make('lat_sec')
                                                    ->label(__('warehouse.branch.lat_sec'))
                                                    ->numeric()
                                                    ->step(0.0000001),
                                                
                                                Forms\Components\TextInput::make('lon_deg')
                                                    ->label(__('warehouse.branch.lon_deg'))
                                                    ->numeric(),
                                                
                                                Forms\Components\TextInput::make('lon_min')
                                                    ->label(__('warehouse.branch.lon_min'))
                                                    ->numeric(),
                                                
                                                Forms\Components\TextInput::make('lon_sec')
                                                    ->label(__('warehouse.branch.lon_sec'))
                                                    ->numeric()
                                                    ->step(0.0000001),
                                            ]),
                                        
                                        Forms\Components\TextInput::make('height')
                                            ->label(__('warehouse.branch.height'))
                                            ->numeric()
                                            ->suffix('متر'),
                                    ])
                                    ->collapsible(),
                            ]),

                        // Additional Information Tab
                        Tab::make(__('warehouse.branch_tabs.additional_info'))
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make(__('warehouse.branch.sections.closure_info'))
                                    ->description(__('warehouse.branch.sections.closure_info_desc'))
                                    ->icon('heroicon-o-calendar-days')
                                    ->iconColor('danger')
                                    ->schema([
                                        Forms\Components\Toggle::make('closed_thursday')
                                            ->label(__('warehouse.branch.closed_thursday'))
                                            ->default(false),
                                        
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\DatePicker::make('closure_date')
                                                    ->jalali()
                                                    ->label(__('warehouse.branch.closure_date')),
                                                
                                                Forms\Components\TextInput::make('closure_document')
                                                    ->label(__('warehouse.branch.closure_document'))
                                                    ->maxLength(255),
                                            ]),
                                        
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('date_closed_thursday')
                                                    ->label(__('warehouse.branch.date_closed_thursday'))
                                                    ->maxLength(255),
                                                
                                                Forms\Components\TextInput::make('date_closed_thursday_end')
                                                    ->label(__('warehouse.branch.date_closed_thursday_end'))
                                                    ->maxLength(255),
                                            ]),
                                    ])
                                    ->collapsible(),

                                Section::make(__('warehouse.branch.sections.images'))
                                    ->description(__('warehouse.branch.sections.images_desc'))
                                    ->icon('heroicon-o-photo')
                                    ->iconColor('secondary')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\FileUpload::make('img_header')
                                                    ->label(__('warehouse.branch.img_header'))
                                                    ->image()
                                                    ->directory('branches/headers'),
                                                
                                                Forms\Components\FileUpload::make('img_building')
                                                    ->label(__('warehouse.branch.img_building'))
                                                    ->image()
                                                    ->directory('branches/buildings'),
                                            ]),
                                    ])
                                    ->collapsible(),
                            ]),
                    ])
                    ->persistTabInQueryString()
                    ->id('branch-form-tabs'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('warehouse.branch.table.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('branch_type_name')
                    ->label(__('warehouse.branch.table.branch_type'))
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'شهرستان' => 'gray',
                        'ستاد' => 'primary',
                        'شعبه' => 'success',
                        'دفترنمایندگی مستقل' => 'warning',
                        'دفترنمایندگی وابسته' => 'info',
                        'مناطق شهری' => 'secondary',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('warehouse.branch.table.phone'))
                    ->searchable()
                    ->icon('heroicon-o-phone'),
                
                Tables\Columns\TextColumn::make('address')
                    ->label(__('warehouse.branch.table.address'))
                    ->searchable()
                    ->limit(50),
                
                Tables\Columns\IconColumn::make('closed_thursday')
                    ->label(__('warehouse.branch.table.closed_thursday'))
                    ->boolean()
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('warehouse.branch.table.created_at'))
                    ->dateTime('j F Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('branch_type')
                    ->label(__('warehouse.branch.filters.branch_type'))
                    ->options([
                        Branch::BRANCH_TYPE_COUNTY => 'شهرستان',
                        Branch::BRANCH_TYPE_HEADQUARTERS => 'ستاد',
                        Branch::BRANCH_TYPE_BRANCH => 'شعبه',
                        Branch::BRANCH_TYPE_INDEPENDENT_OFFICE => 'دفترنمایندگی مستقل',
                        Branch::BRANCH_TYPE_DEPENDENT_OFFICE => 'دفترنمایندگی وابسته',
                        Branch::BRANCH_TYPE_URBAN_AREA => 'مناطق شهری',
                    ])
                    ->multiple(),
                
                Tables\Filters\TernaryFilter::make('closed_thursday')
                    ->label(__('warehouse.branch.filters.closed_thursday'))
                    ->placeholder(__('warehouse.branch.filters.all'))
                    ->trueLabel(__('warehouse.branch.filters.closed'))
                    ->falseLabel(__('warehouse.branch.filters.open')),
            ])
            ->actions([
                ViewAction::make()
                    ->label(__('warehouse.branch.actions.view'))
                    ->icon('heroicon-o-eye'),
                EditAction::make()
                    ->label(__('warehouse.branch.actions.edit'))
                    ->icon('heroicon-o-pencil'),
                DeleteAction::make()
                    ->label(__('warehouse.branch.actions.delete'))
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('warehouse.branch.actions.delete_selected'))
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
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'view' => Pages\ViewBranch::route('/{record}'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
