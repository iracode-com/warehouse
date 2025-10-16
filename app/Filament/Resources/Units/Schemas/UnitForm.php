<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات واحد شمارش')
                    ->description('اطلاعات پایه واحد اندازه‌گیری')
                    ->icon('heroicon-o-scale')
                    ->iconColor('primary')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('نام واحد')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-tag')
                                    ->placeholder('مثال: کیلوگرم')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('symbol')
                                    ->label('نماد')
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->placeholder('مثال: kg')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('code')
                                    ->label('کد واحد')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-identification')
                                    ->placeholder('مثال: UNIT-001')
                                    ->columnSpan(1),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('category')
                                    ->label('دسته‌بندی')
                                    ->options([
                                        'weight' => 'وزن',
                                        'length' => 'طول',
                                        'volume' => 'حجم',
                                        'area' => 'مساحت',
                                        'piece' => 'تعدادی',
                                        'package' => 'بسته‌بندی',
                                        'other' => 'سایر',
                                    ])
                                    ->searchable()
                                    ->prefixIcon('heroicon-o-folder')
                                    ->placeholder('انتخاب دسته‌بندی')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label('ترتیب نمایش')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->prefixIcon('heroicon-o-arrows-up-down')
                                    ->helperText('عدد کوچکتر = اولویت بالاتر')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Toggle::make('is_active')
                            ->label('وضعیت فعال')
                            ->default(true)
                            ->inline(false)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('توضیحات')
                            ->rows(3)
                            ->placeholder('توضیحات تکمیلی درباره این واحد...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
