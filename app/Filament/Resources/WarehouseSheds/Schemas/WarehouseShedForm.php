<?php

namespace App\Filament\Resources\WarehouseSheds\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Dotswan\MapPicker\Fields\Map;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class WarehouseShedForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('مدیریت سوله')
                    ->tabs([
                        Tab::make('اطلاعات پایه')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('اطلاعات کلی')
                                    ->description('اطلاعات اصلی سوله')
                                    ->icon('heroicon-o-information-circle')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                TextInput::make('name')
                                                    ->label('نام سوله')
                                                    ->required()
                                                    ->maxLength(255),

                                                TextInput::make('code')
                                                    ->label('کد سوله')
                                                    ->required()
                                                    ->unique(ignoreRecord: true)
                                                    ->maxLength(50),
                                            ]),

                Textarea::make('description')
                                            ->label('توضیحات')
                                            ->rows(3)
                    ->columnSpanFull(),
                                    ])
                                    ->collapsible(),

                                Section::make('وضعیت')
                                    ->description('وضعیت فعال/غیرفعال سوله')
                                    ->icon('heroicon-o-power')
                                    ->schema([
                                        Toggle::make('status')
                                            ->label('وضعیت فعال')
                                            ->default(true)
                    ->required(),
                                    ])
                                    ->collapsible(),
                            ]),

                        Tab::make('ابعاد و متراژ')
                            ->icon('heroicon-o-calculator')
                            ->schema([
                                Section::make('ابعاد سوله')
                                    ->description('طول، عرض و ارتفاع سوله')
                                    ->icon('heroicon-o-calculator')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('length')
                                                    ->label('طول (متر)')
                                                    ->numeric()
                                                    ->suffix('متر'),

                                                TextInput::make('width')
                                                    ->label('عرض (متر)')
                                                    ->numeric()
                                                    ->suffix('متر'),

                                                TextInput::make('height')
                                                    ->label('ارتفاع (متر)')
                                                    ->numeric()
                                                    ->suffix('متر'),
                                            ]),
                                    ])
                                    ->collapsible(),

                                Section::make('مساحت و حجم')
                                    ->description('مساحت و حجم کل سوله')
                                    ->icon('heroicon-o-squares-2x2')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                TextInput::make('area')
                                                    ->label('مساحت کل (متر مربع)')
                                                    ->numeric()
                                                    ->suffix('متر مربع'),

                                                TextInput::make('volume')
                                                    ->label('حجم (متر مکعب)')
                                                    ->numeric()
                                                    ->suffix('متر مکعب'),
                                            ]),
                                    ])
                                    ->collapsible(),
                            ]),

                        Tab::make('نوع سازه')
                            ->icon('heroicon-o-building-storefront')
                            ->schema([
                                Section::make('مشخصات سازه')
                                    ->description('نوع سازه و سقف سوله')
                                    ->icon('heroicon-o-building-storefront')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('structure_type')
                                                    ->label('نوع سازه')
                                                    ->options([
                                                        'steel' => 'فلزی',
                                                        'concrete' => 'بتنی',
                                                        'prefabricated' => 'پیش‌ساخته',
                                                        'mixed' => 'ترکیبی',
                                                    ])
                                                    ->searchable(),

                                                Select::make('roof_type')
                                                    ->label('نوع سقف')
                                                    ->options([
                                                        'flat' => 'مسطح',
                                                        'sloped' => 'شیب‌دار',
                                                        'arched' => 'قوسی',
                                                        'dome' => 'گنبدی',
                                                    ])
                                                    ->searchable(),
                                            ]),
                                    ])
                                    ->collapsible(),

                                Section::make('جزئیات سازه')
                                    ->description('فونداسیون و دیوارها')
                                    ->icon('heroicon-o-wrench-screwdriver')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('foundation_type')
                                                    ->label('نوع فونداسیون')
                                                    ->options([
                                                        'concrete' => 'بتنی',
                                                        'steel' => 'فلزی',
                                                        'mixed' => 'ترکیبی',
                                                        'prefabricated' => 'پیش‌ساخته',
                                                        'other' => 'سایر',
                                                    ])
                                                    ->searchable(),

                                                Select::make('wall_material')
                                                    ->label('جنس دیوارها')
                                                    ->options([
                                                        'concrete' => 'بتن',
                                                        'steel' => 'فلز',
                                                        'brick' => 'آجر',
                                                        'block' => 'بلوک',
                                                        'wood' => 'چوب',
                                                        'composite' => 'کامپوزیت',
                                                        'other' => 'سایر',
                                                    ])
                                                    ->searchable(),
                                            ]),
                                    ])
                                    ->collapsible(),
                            ]),

                        Tab::make('موقعیت جغرافیایی')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Section::make('آدرس سوله')
                                    ->description('آدرس کامل سوله')
                                    ->icon('heroicon-o-map-pin')
                                    ->schema([
                                        TextInput::make('address')
                                            ->label('آدرس')
                                            ->maxLength(500)
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),

                                Section::make('مختصات جغرافیایی')
                                    ->description('طول و عرض جغرافیایی سوله')
                                    ->icon('heroicon-o-globe-americas')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Map::make('shed_location')
                                                    ->showMarker(true)
                                                    ->liveLocation(true)
                                                    ->label('موقعیت سوله روی نقشه')
                                                    ->columnSpanFull()
                                                    ->defaultLocation(latitude: "35.7219", longitude: "51.3347")
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
                                                        'border-radius: 50px'
                                                    ]),
                                            ]),

                                        Grid::make(2)
                                            ->schema([
                TextInput::make('latitude')
                                                    ->label('عرض جغرافیایی')
                                                    ->numeric()
                                                    ->step(0.00000001)
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                        $set('shed_location.lat', $state);
                                                    }),

                TextInput::make('longitude')
                                                    ->label('طول جغرافیایی')
                                                    ->numeric()
                                                    ->step(0.00000001)
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                        $set('shed_location.lng', $state);
                                                    }),
                                            ]),
                                    ])
                                    ->collapsible(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
