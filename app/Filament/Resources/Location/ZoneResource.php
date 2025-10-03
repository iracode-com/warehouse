<?php

namespace App\Filament\Resources\Location;

use App\Filament\Resources\Location\ZoneResource\Pages;
use App\Models\Location\Zone;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

class ZoneResource extends Resource
{
    protected static ?string $model = Zone::class;

    public static function getNavigationIcon(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return 'مناطق';
    }

    public static function getModelLabel(): string
    {
        return 'منطقه';
    }

    public static function getPluralModelLabel(): string
    {
        return 'مناطق';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'محل قرارگیری کالا';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function canAccess(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات منطقه')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('کد منطقه')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('name')
                            ->label('نام منطقه')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('warehouse_id')
                            ->label('انبار')
                            ->relationship('warehouse', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('zone_type')
                            ->label('نوع منطقه')
                            ->options(__('common-options.zone_type'))
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('توضیحات')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('ظرفیت و شرایط')
                    ->schema([
                        Forms\Components\TextInput::make('capacity_cubic_meters')
                            ->label('ظرفیت (مترمکعب)')
                            ->numeric()
                            ->step(0.01),

                        Forms\Components\TextInput::make('capacity_pallets')
                            ->label('ظرفیت (تعداد پالت)')
                            ->numeric()
                            ->integer(),

                        Forms\Components\TextInput::make('temperature')
                            ->label('دمای منطقه (درجه سانتی‌گراد)')
                            ->numeric()
                            ->step(0.01),

                        Forms\Components\Toggle::make('is_active')
                            ->label('وضعیت فعال')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('کد منطقه')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام منطقه')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('warehouse.title')
                    ->label('انبار')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('zone_type_label')
                    ->label('نوع منطقه')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ذخیره‌سازی سرد' => 'info',
                        'ذخیره‌سازی گرم' => 'warning',
                        'عمومی' => 'success',
                        'مواد خطرناک' => 'danger',
                        'لوازم یدکی خودرو' => 'primary',
                        'تجهیزات امدادی' => 'secondary',
                        'موقت' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('capacity_cubic_meters')
                    ->label('ظرفیت (م³)')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('capacity_pallets')
                    ->label('ظرفیت (پالت)')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('temperature')
                    ->label('دما (°C)')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('وضعیت')
                    ->boolean(),

                Tables\Columns\TextColumn::make('corridors_count')
                    ->label('تعداد راهروها')
                    ->counts('corridors')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('warehouse_id')
                    ->label('انبار')
                    ->relationship('warehouse', 'title'),

                Tables\Filters\SelectFilter::make('zone_type')
                    ->label('نوع منطقه')
                    ->options([
                        'cold_storage' => 'ذخیره‌سازی سرد',
                        'hot_storage' => 'ذخیره‌سازی گرم',
                        'general' => 'عمومی',
                        'hazardous_materials' => 'مواد خطرناک',
                        'auto_parts' => 'لوازم یدکی خودرو',
                        'emergency_supplies' => 'تجهیزات امدادی',
                        'temporary' => 'موقت',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('وضعیت فعال'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
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
            'index' => Pages\ListZones::route('/'),
            'create' => Pages\CreateZone::route('/create'),
            'view' => Pages\ViewZone::route('/{record}'),
            'edit' => Pages\EditZone::route('/{record}/edit'),
        ];
    }
}
