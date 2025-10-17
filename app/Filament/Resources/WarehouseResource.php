<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseResource\Pages;
use App\Models\Warehouse;
use Dotswan\MapPicker\Fields\Map;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('navigation.warehouses');
    }

    public static function getModelLabel(): string
    {
        return __('navigation.warehouse');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.warehouses');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('warehouse.navigation_groups.warehouse_management');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make(__('warehouse.sections.basic_info'))
                    ->description(__('warehouse.sections.basic_info_desc'))
                    ->icon('heroicon-o-building-office')
                    ->iconColor('primary')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('warehouse.title'))
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                // Forms\Components\Select::make('shed_id')
                                //     ->label('سوله')
                                //     ->relationship('shed', 'name')
                                //     ->searchable()
                                //     ->preload()
                                //     ->placeholder('انتخاب سوله')
                                //     ->required(),

                                Forms\Components\Select::make('usage_types')
                                    ->label(__('warehouse.usage_type'))
                                    ->options(__('warehouse.usage_types'))
                                    ->multiple()
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('انتخاب انواع کاربری انبار'),

                                Forms\Components\Select::make('ownership_type')
                                    ->label(__('warehouse.ownership_type'))
                                    ->options(__('warehouse.ownership_types'))
                                    ->required()
                                    ->searchable(),

                                Forms\Components\TextInput::make('telephone')
                                    ->label(__('warehouse.telephone'))
                                    ->tel()
                                    ->maxLength(255),

                                Forms\Components\Select::make('warehouse_standard')
                                    ->label('وضعیت استاندارد')
                                    ->options(__('common-options.standard_status'))
                                    ->placeholder('انتخاب کنید')
                                    ->required()
                                    ->searchable(),

                                Forms\Components\TextInput::make('shed_number')
                                    ->label('شماره سوله انبار')
                                    ->numeric(),

                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('warehouse.sections.geographic_info'))
                    ->description(__('warehouse.geographic_info_desc'))
                    ->icon('heroicon-o-globe-americas')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Map::make('warehouse_location')
                                    ->showMarker(true)
                                    ->liveLocation(true)
                                    ->label(__('warehouse.warehouse_location'))
                                    ->columnSpanFull()
                                    ->defaultLocation(latitude: '35.7219', longitude: '51.3347')
                                    ->afterStateUpdated(function (Set $set, ?array $state, $livewire): void {
                                        if ($state) {
                                            $set('latitude', $state['lat']);
                                            $set('longitude', $state['lng']);
                                        }
                                    })
                                    ->live()
                                    ->reactive()
                                    ->extraStyles([
                                        'min-height: 50vh',
                                        'border-radius: 50px',
                                    ]),
                                Forms\Components\TextInput::make('longitude')
                                    ->label('طول جغرافیایی')
                                    ->numeric()
                                    ->live()
                                    ->reactive()
                                    ->suffix('درجه'),

                                Forms\Components\TextInput::make('latitude')
                                    ->label('عرض جغرافیایی')
                                    ->numeric()
                                    ->live()
                                    ->reactive()
                                    ->suffix('درجه'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('longitude_e')
                                    ->label('طول جغرافیایی (E)')
                                    ->numeric()
                                    ->step(0.0000001)
                                    ->suffix('درجه')
                                    ->live()
                                    ->reactive()
                                    ->afterStateUpdated(function (Set $set, ?float $state, $livewire): void {
                                        if ($state) {
                                            $set('longitude', $state);
                                        }
                                    }),

                                Forms\Components\TextInput::make('latitude_n')
                                    ->label('عرض جغرافیایی (N)')
                                    ->numeric()
                                    ->step(0.0000001)
                                    ->suffix('درجه')
                                    ->live()
                                    ->reactive()
                                    ->afterStateUpdated(function (Set $set, ?float $state, $livewire): void {
                                        if ($state) {
                                            $set('latitude', $state);
                                        }
                                    }),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('altitude')
                                    ->label('ارتفاع از سطح دریا')
                                    ->numeric()
                                    ->step(0.01)
                                    ->suffix('متر'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('warehouse.sections.temporal_info'))
                    ->description(__('warehouse.sections.temporal_info_desc'))
                    ->icon('heroicon-o-calendar-days')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('establishment_year')
                                    ->label('سال تاسیس انبار')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1300)
                                    ->maxValue(1500)
                                    ->suffix('ه.ش'),

                                Forms\Components\TextInput::make('construction_year')
                                    ->label('سال ساخت')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1300)
                                    ->maxValue(1500)
                                    ->suffix('ه.ش'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('پرسنل انبار')
                    ->description('انبارداران و کارکنان انبار')
                    ->icon('heroicon-o-user-group')
                    ->iconColor('warning')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('personnel')
                            ->label('انبارداران')
                            ->relationship('personnel', 'full_name')
                            ->multiple()
                            ->searchable(['name', 'family', 'personnel_code'])
                            ->preload()
                            ->prefixIcon('heroicon-o-users')
                            ->helperText('انتخاب پرسنل انبار (چند انتخابی)')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('ارزیابی انبار')
                    ->description('ارزیابی و درجه‌بندی انبار')
                    ->icon('heroicon-o-chart-bar')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('وضعیت انبار')
                                    ->options(\App\Models\Warehouse::getStatusOptions())
                                    ->required()
                                    ->default(\App\Models\Warehouse::STATUS_ACTIVE)
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\TextInput::make('provincial_risk_percentage')
                                    ->label('درصد خطرپذیری استانی')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->step(0.01)
                                    ->suffix('%'),

                                Forms\Components\Select::make('approved_grade')
                                    ->label('درجه مصوب')
                                    ->options(\App\Models\Warehouse::getApprovedGradeOptions())
                                    ->searchable()
                                    ->preload()
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Select::make('natural_hazards')
                            ->label(__('warehouse.natural_hazards'))
                            ->options(__('warehouse.natural_hazards_types'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->placeholder('انتخاب مخاطرات طبیعی')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('اطلاعات موقعیت و دسترسی')
                    ->description('اطلاعات دسترسی و امکانات انبار')
                    ->icon('heroicon-o-map')
                    ->iconColor('success')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('urban_location')
                                    ->label(__('warehouse.urban_location'))
                                    ->options(__('warehouse.urban_location_types'))
                                    ->searchable()
                                    ->placeholder('انتخاب محدوده شهری'),

                                Forms\Components\Select::make('main_road_access')
                                    ->label(__('warehouse.main_road_access'))
                                    ->options(__('warehouse.main_road_access_types'))
                                    ->searchable()
                                    ->placeholder('انتخاب وضعیت'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('heavy_vehicle_access')
                                    ->label(__('warehouse.heavy_vehicle_access'))
                                    ->options(__('warehouse.heavy_vehicle_access_types'))
                                    ->searchable()
                                    ->placeholder('انتخاب وضعیت'),

                                Forms\Components\Select::make('parking_facilities')
                                    ->label(__('warehouse.parking_facilities'))
                                    ->options(__('warehouse.parking_facilities_types'))
                                    ->searchable()
                                    ->placeholder('انتخاب نوع پارکینگ'),
                            ]),

                        Forms\Components\Select::make('terminal_proximity')
                            ->label(__('warehouse.terminal_proximity'))
                            ->options(__('warehouse.terminal_proximity_types'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->placeholder('انتخاب نوع پایانه')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('utilities')
                            ->label(__('warehouse.utilities'))
                            ->options(__('warehouse.utilities_types'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->placeholder('انتخاب انشعابات')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('neighboring_organizations')
                            ->label(__('warehouse.neighboring_organizations'))
                            ->options(__('warehouse.neighboring_organizations_types'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->placeholder('انتخاب ارگان‌های همجوار')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make(__('warehouse.sections.location_info'))
                    ->description(__('warehouse.sections.location_info_desc'))
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('success')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('province_id')
                                    ->label(__('warehouse.province_id'))
                                    ->options(function () {
                                        return \App\Models\Location\Province::all()->pluck('name', 'id')->toArray();
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\Select::make('city_id')
                                    ->label(__('warehouse.city_id'))
                                    ->options(function () {
                                        return \App\Models\Location\City::all()->pluck('name', 'id')->toArray();
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\Select::make('town_id')
                                    ->label(__('warehouse.town_id'))
                                    ->options(function () {
                                        return \App\Models\Location\Town::all()->pluck('name', 'id')->toArray();
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\Select::make('base_id')
                                    ->label(__('warehouse.base'))
                                    ->relationship('base', 'name')
                                    ->getOptionLabelFromRecordUsing(fn($record) => $record->display_name)
                                    ->searchable(['name->fa', 'name->en', 'coding'])
                                    ->required()
                                    ->preload(),

                                Forms\Components\Select::make('branch_id')
                                    ->label(__('warehouse.branch_name'))
                                    ->options(function () {
                                        return \App\Models\Branch::all()->pluck('name', 'id')->toArray();
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\Select::make('village_id')
                                    ->label(__('warehouse.village_id'))
                                    ->options(function () {
                                        return \App\Models\Location\Village::all()->pluck('name', 'id')->toArray();
                                    })
                                    ->searchable()
                                    ->preload(),
                            ]),

                        Forms\Components\Textarea::make('address')
                            ->label(__('warehouse.address'))
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('distance_to_airport')
                                    ->label('فاصله تا فرودگاه')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->suffix('کیلومتر'),

                                Forms\Components\TextInput::make('distance_to_railway_station')
                                    ->label('فاصله تا ایستگاه راه آهن')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->suffix('کیلومتر'),

                                Forms\Components\TextInput::make('distance_to_helicopter_hangar')
                                    ->label('فاصله تا آشیانه هلیکوپتر')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->suffix('کیلومتر'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('warehouse.sections.branch_distance'))
                    ->description(__('warehouse.sections.branch_distance_desc'))
                    ->icon('heroicon-o-map')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('nearest_branch_1_id')
                                    ->label('شعبه اول')
                                    ->options(function () {
                                        return \App\Models\Branch::all()->pluck('name', 'id')->toArray();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('انتخاب شعبه'),

                                Forms\Components\TextInput::make('distance_to_branch_1')
                                    ->label('فاصله تا شعبه اول')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->suffix('کیلومتر'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('nearest_branch_2_id')
                                    ->label('شعبه دوم')
                                    ->options(function () {
                                        return \App\Models\Branch::all()->pluck('name', 'id')->toArray();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('انتخاب شعبه'),

                                Forms\Components\TextInput::make('distance_to_branch_2')
                                    ->label('فاصله تا شعبه دوم')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->suffix('کیلومتر'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('warehouse.sections.forklift_info'))
                    ->description(__('warehouse.sections.forklift_info_desc'))
                    ->icon('heroicon-o-truck')
                    ->iconColor('warning')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('diesel_forklift_healthy_count')
                                    ->label('لیفتراک دیزلی - تعداد سالم')
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->suffix('عدد'),

                                Forms\Components\TextInput::make('diesel_forklift_defective_count')
                                    ->label('لیفتراک دیزلی - تعداد معیوب')
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->suffix('عدد'),

                                Forms\Components\TextInput::make('gasoline_forklift_healthy_count')
                                    ->label('لیفتراک بنزینی - تعداد سالم')
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->suffix('عدد'),

                                Forms\Components\TextInput::make('gasoline_forklift_defective_count')
                                    ->label('لیفتراک بنزینی - تعداد معیوب')
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->suffix('عدد'),

                                Forms\Components\TextInput::make('gas_forklift_healthy_count')
                                    ->label('لیفتراک گازسوز - تعداد سالم')
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->suffix('عدد'),

                                Forms\Components\TextInput::make('gas_forklift_defective_count')
                                    ->label('لیفتراک گازسوز - تعداد معیوب')
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->suffix('عدد'),

                                Forms\Components\TextInput::make('electrical_forklift_healthy_count')
                                    ->label('لیفتراک برقی - تعداد سالم')
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->suffix('عدد'),

                                Forms\Components\TextInput::make('electrical_forklift_defective_count')
                                    ->label('لیفتراک برقی - تعداد معیوب')
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->suffix('عدد'),

                                Forms\Components\TextInput::make('dual_fuel_forklift_healthy_count')
                                    ->label('لیفتراک دوگانه سوز - تعداد سالم')
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->suffix('عدد'),

                                Forms\Components\TextInput::make('dual_fuel_forklift_defective_count')
                                    ->label('لیفتراک دوگانه سوز - تعداد معیوب')
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->suffix('عدد'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make(__('warehouse.sections.other_info'))
                    ->description(__('warehouse.sections.other_info_desc'))
                    ->icon('heroicon-o-shield-check')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('warehouse_insurance')
                                    ->label('بیمه کالاهای داخل انبار')
                                    ->options(__('common-options.yes_no'))
                                    ->required()
                                    ->searchable(),

                                Forms\Components\Select::make('building_insurance')
                                    ->label('بیمه ابنیه')
                                    ->options(__('common-options.yes_no'))
                                    ->required()
                                    ->searchable(),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('fire_suppression_system')
                                    ->label('سیستم اطفا حریق')
                                    ->options(__('common-options.system_status'))
                                    ->placeholder('انتخاب کنید')
                                    ->searchable(),

                                Forms\Components\Select::make('fire_alarm_system')
                                    ->label('سیستم اعلان حریق')
                                    ->options(__('common-options.system_status'))
                                    ->placeholder('انتخاب کنید')
                                    ->searchable(),

                                Forms\Components\Select::make('cctv_system')
                                    ->label('دوربین مدار بسته')
                                    ->options(__('common-options.system_status'))
                                    ->placeholder('انتخاب کنید')
                                    ->searchable(),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('lighting_system')
                                    ->label('سیستم روشنایی')
                                    ->options(__('common-options.system_status'))
                                    ->placeholder('انتخاب کنید')
                                    ->searchable(),

                                Forms\Components\Select::make('ram_rack')
                                    ->label('رام و راک')
                                    ->options(__('common-options.yes_no'))
                                    ->required()
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        if ($state !== 'yes') {
                                            $set('ram_rack_count', null);
                                        }
                                    }),

                                Forms\Components\TextInput::make('ram_rack_count')
                                    ->label('تعداد رام راک')
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix('عدد')
                                    ->visible(fn($get) => $get('ram_rack') === 'yes')
                                    ->required(fn($get) => $get('ram_rack') === 'yes'),

                                Forms\Components\TextInput::make('fire_extinguishers_count')
                                    ->label('تعداد کپسول آتش نشانی')
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix('عدد'),
                            ]),

                        Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('ramp_length')
                                    ->label('طول رمپ')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->columnSpan(2)
                                    ->suffix('متر'),

                                Forms\Components\TextInput::make('ramp_height')
                                    ->label('ارتفاع رمپ')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->columnSpan(2)
                                    ->suffix('متر'),

                                Forms\Components\TextInput::make('building_length')
                                    ->label('طول در حال ساخت')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->columnSpan(2)
                                    ->suffix('متر'),

                                Forms\Components\TextInput::make('building_width')
                                    ->label('عرض در حال ساخت')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->columnSpan(2)
                                    ->suffix('متر'),

                                Forms\Components\TextInput::make('building_height')
                                    ->label('ارتفاع در حال ساخت')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->columnSpan(2)
                                    ->suffix('متر'),

                                Forms\Components\TextInput::make('building_metrage')
                                    ->label('متراژ در حال ساخت')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->columnSpan(2)
                                    ->suffix('متر'),

                                Forms\Components\TextInput::make('small_inventory_count')
                                    ->label('موجودی کوچک')
                                    ->numeric()
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('large_inventory_count')
                                    ->label('موجودی بزرگ')
                                    ->numeric()
                                    ->columnSpan(2),
                            ]),

                        // Additional warehouse infrastructure fields
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('flooring_type')
                                    ->label('کف سازی مناسب انبار')
                                    ->options(Warehouse::getFlooringTypeOptions())
                                    ->placeholder('انتخاب کنید')
                                    ->searchable(),

                                Forms\Components\Select::make('window_condition')
                                    ->label('دارای پنجره های مناسب')
                                    ->options(Warehouse::getWindowConditionOptions())
                                    ->placeholder('انتخاب کنید')
                                    ->searchable(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('loading_platform')
                                    ->label('سکوی بارانداز مناسب و استاندارد')
                                    ->options(Warehouse::getLoadingPlatformOptions())
                                    ->placeholder('انتخاب کنید')
                                    ->searchable(),

                                Forms\Components\Select::make('external_fencing')
                                    ->label('حصارکشی محوطه بیرونی')
                                    ->options(Warehouse::getExternalFencingOptions())
                                    ->placeholder('انتخاب کنید')
                                    ->searchable(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('ventilation_system')
                                    ->label('تهویه هوای مناسب')
                                    ->options(Warehouse::getVentilationSystemOptions())
                                    ->placeholder('انتخاب کنید')
                                    ->searchable(),

                                Forms\Components\Select::make('wall_distance')
                                    ->label('رعایت فاصله دیوارهای انبار')
                                    ->options(Warehouse::getWallDistanceOptions())
                                    ->placeholder('انتخاب کنید')
                                    ->searchable(),
                            ]),

                        Grid::make(1)
                            ->schema([
                                Forms\Components\Select::make('security_guard')
                                    ->label('وضعیت نگهبانی/سرایداری')
                                    ->options(Warehouse::getSecurityGuardOptions())
                                    ->placeholder('انتخاب کنید')
                                    ->searchable()
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Textarea::make('warehouse_info')
                            ->label(__('warehouse.warehouse_info'))
                            ->rows(3)
                            ->columnSpanFull(),

                    ])
                    ->collapsible(),

                Section::make('سیستم‌های امنیتی و کنترل')
                    ->description('سیستم‌های امنیتی و کنترل پیشرفته انبار')
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('success')
                    ->columnSpanFull()
                    ->visible(false)
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('door_sms_system')
                                    ->label('سیستم پیامک درب انبار')
                                    ->options([
                                        'yes' => 'دارد',
                                        'no' => 'ندارد',
                                    ])
                                    ->placeholder('انتخاب کنید')
                                    ->searchable()
                                    ->helperText('سیستم ارسال پیامک در صورت باز شدن درب انبار'),

                                Forms\Components\Select::make('electric_door_system')
                                    ->label('سیستم درب برقی')
                                    ->options(__('common-options.door_system'))
                                    ->placeholder('انتخاب کنید')
                                    ->searchable()
                                    ->helperText('نوع سیستم باز و بسته کردن درب سوله'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('remote_lighting_system')
                                    ->label('سیستم روشنایی کنترل از راه دور')
                                    ->options([
                                        'yes' => 'دارد',
                                        'no' => 'ندارد',
                                    ])
                                    ->placeholder('انتخاب کنید')
                                    ->searchable()
                                    ->helperText('کنترل روشنایی از راه دور در نقاط مختلف انبار'),

                                Forms\Components\Select::make('warehouse_marking_system')
                                    ->label('سیستم خط کشی انبار')
                                    ->options([
                                        'yes' => 'دارد',
                                        'no' => 'ندارد',
                                    ])
                                    ->placeholder('انتخاب کنید')
                                    ->searchable()
                                    ->helperText('خط کشی و بلوک بندی برای چیدمان کالاها'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('warning_signs_inside')
                                    ->label('علایم هشدار دهنده داخل انبار')
                                    ->options([
                                        'yes' => 'دارد',
                                        'no' => 'ندارد',
                                    ])
                                    ->placeholder('انتخاب کنید')
                                    ->searchable(),

                                Forms\Components\Select::make('warning_signs_outside')
                                    ->label('علایم هشدار دهنده بیرون انبار')
                                    ->options([
                                        'yes' => 'دارد',
                                        'no' => 'ندارد',
                                    ])
                                    ->placeholder('انتخاب کنید')
                                    ->searchable(),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('warehouse.table.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('province.name')
                    ->label(__('warehouse.table.province'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('branch.name')
                    ->label(__('warehouse.table.branch'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('base.display_name')
                    ->label(__('warehouse.base'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('city.name')
                    ->label(__('warehouse.table.city'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('usage_types')
                    ->label(__('warehouse.table.usage'))
                    ->getStateUsing(function ($record) {
                        return $record->usageTypes()->pluck('usage_type')->map(function ($type) {
                            return __('warehouse.usage_types.' . $type);
                        })->join(', ');
                    })
                    ->badge()
                    ->separator(','),

                Tables\Columns\TextColumn::make('area')
                    ->label(__('warehouse.table.area'))
                    ->numeric()
                    ->sortable()
                    ->suffix(' ' . __('warehouse.units.square_meter'))
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('natural_hazards')
                    ->label(__('warehouse.natural_hazards'))
                    ->getStateUsing(function ($record) {
                        if (!$record->natural_hazards) {
                            return 'بدون مخاطره';
                        }

                        return collect($record->natural_hazards)->map(function ($hazard) {
                            return __('warehouse.natural_hazards_types.' . $hazard);
                        })->join(', ');
                    })
                    ->badge()
                    ->separator(',')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('urban_location')
                    ->label(__('warehouse.urban_location'))
                    ->getStateUsing(function ($record) {
                        return $record->urban_location ? __('warehouse.urban_location_types.' . $record->urban_location) : '';
                    })
                    ->badge()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('utilities')
                    ->label(__('warehouse.utilities'))
                    ->getStateUsing(function ($record) {
                        if (!$record->utilities) {
                            return 'بدون انشعاب';
                        }

                        return collect($record->utilities)->map(function ($utility) {
                            return __('warehouse.utilities_types.' . $utility);
                        })->join(', ');
                    })
                    ->badge()
                    ->separator(',')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('ownership_type')
                    ->label(__('warehouse.table.ownership'))
                    ->formatStateUsing(fn(string $state): string => __('warehouse.ownership_types.' . $state))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'owned' => 'success',
                        'rented' => 'warning',
                        'donated' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('warehouse_insurance')
                    ->label(__('warehouse.table.insurance'))
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('warehouse.table.created_at'))
                    ->dateTime('j F Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('base_id')
                    ->label(__('warehouse.base'))
                    ->relationship('base', 'name')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->display_name)
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('usage_type')
                    ->label(__('warehouse.filters.usage_type'))
                    ->multiple()
                    ->options(__('warehouse.usage_types')),

                Tables\Filters\SelectFilter::make('ownership_type')
                    ->label(__('warehouse.filters.ownership_type'))
                    ->multiple()
                    ->options(__('warehouse.ownership_types')),

                Tables\Filters\SelectFilter::make('province_id')
                    ->label(__('warehouse.filters.province'))
                    ->options(function () {
                        return \App\Models\Location\Province::all()->pluck('name', 'id')->toArray();
                    })
                    ->multiple()
                    ->searchable(),

                Tables\Filters\TernaryFilter::make('warehouse_insurance')
                    ->label(__('warehouse.filters.insurance'))
                    ->placeholder(__('warehouse.filters.all'))
                    ->trueLabel(__('warehouse.filters.has'))
                    ->falseLabel(__('warehouse.filters.has_not')),

                Tables\Filters\Filter::make('area_range')
                    ->form([
                        Forms\Components\TextInput::make('area_from')
                            ->label('متراژ از')
                            ->numeric()
                            ->suffix('متر مربع'),
                        Forms\Components\TextInput::make('area_to')
                            ->label('متراژ تا')
                            ->numeric()
                            ->suffix('متر مربع'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['area_from'], fn($query, $value) => $query->where('area', '>=', $value))
                            ->when($data['area_to'], fn($query, $value) => $query->where('area', '<=', $value));
                    }),
            ])
            ->actions([
                ViewAction::make()
                    ->label(__('warehouse.table.view'))
                    ->icon('heroicon-o-eye'),
                EditAction::make()
                    ->label(__('warehouse.table.edit'))
                    ->icon('heroicon-o-pencil'),
                DeleteAction::make()
                    ->label(__('warehouse.table.delete'))
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('warehouse.table.delete_selected'))
                        ->requiresConfirmation(),
                ]),
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
            'index' => Pages\ListWarehouses::route('/'),
            'create' => Pages\CreateWarehouse::route('/create'),
            'view' => Pages\ViewWarehouse::route('/{record}'),
            'edit' => Pages\EditWarehouse::route('/{record}/edit'),
        ];
    }
}
