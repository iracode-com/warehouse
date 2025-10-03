<?php

namespace App\Filament\Resources\Location;

use App\Filament\Resources\Location\ShelfLevelResource\Pages;
use App\Models\Location\ShelfLevel;
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

class ShelfLevelResource extends Resource
{
    protected static ?string $model = ShelfLevel::class;

    public static function getNavigationIcon(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return 'طبقات قفسه';
    }

    public static function getModelLabel(): string
    {
        return 'طبقه قفسه';
    }

    public static function getPluralModelLabel(): string
    {
        return 'طبقات قفسه';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'محل قرارگیری کالا';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function canAccess(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات طبقه')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('کد طبقه')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('name')
                            ->label('نام طبقه')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('rack_id')
                            ->label('قفسه')
                            ->relationship('rack', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('level_number')
                            ->label('شماره طبقه')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(1),

                        Forms\Components\Select::make('allowed_product_type')
                            ->label('نوع کالای مجاز')
                            ->options(__('common-options.product_type'))
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('توضیحات')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('ابعاد و ظرفیت')
                    ->schema([
                        Forms\Components\TextInput::make('height')
                            ->label('ارتفاع (متر)')
                            ->required()
                            ->numeric()
                            ->step(0.01),

                        Forms\Components\TextInput::make('width')
                            ->label('عرض (متر)')
                            ->required()
                            ->numeric()
                            ->step(0.01),

                        Forms\Components\TextInput::make('depth')
                            ->label('عمق (متر)')
                            ->required()
                            ->numeric()
                            ->step(0.01),

                        Forms\Components\TextInput::make('max_weight')
                            ->label('حداکثر وزن قابل تحمل (کیلوگرم)')
                            ->required()
                            ->numeric()
                            ->step(0.01),

                        Forms\Components\TextInput::make('current_weight')
                            ->label('وزن فعلی (کیلوگرم)')
                            ->numeric()
                            ->step(0.01)
                            ->default(0),

                        Forms\Components\Select::make('occupancy_status')
                            ->label('وضعیت اشغال')
                            ->options(__('common-options.occupancy_status'))
                            ->default('empty')
                            ->required(),

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
                    ->label('کد طبقه')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام طبقه')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rack.name')
                    ->label('قفسه')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rack.corridor.name')
                    ->label('راهرو')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rack.corridor.zone.name')
                    ->label('منطقه')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rack.corridor.zone.warehouse.title')
                    ->label('انبار')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('level_number')
                    ->label('شماره طبقه')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('allowed_product_type_label')
                    ->label('نوع کالای مجاز')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'عمومی' => 'success',
                        'مواد خطرناک' => 'danger',
                        'قطعات یدکی' => 'primary',
                        'تجهیزات امدادی' => 'warning',
                        'شکننده' => 'info',
                        'سنگین' => 'secondary',
                        'حساس به دما' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('occupancy_status_label')
                    ->label('وضعیت اشغال')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'خالی' => 'success',
                        'نیمه‌پر' => 'warning',
                        'پر' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('current_weight')
                    ->label('وزن فعلی (کگ)')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('max_weight')
                    ->label('حداکثر وزن (کگ)')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('available_weight')
                    ->label('وزن قابل استفاده (کگ)')
                    ->getStateUsing(fn (ShelfLevel $record): float => $record->available_weight)
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('occupancy_percentage')
                    ->label('درصد اشغال')
                    ->getStateUsing(fn (ShelfLevel $record): float => $record->occupancy_percentage)
                    ->numeric()
                    ->formatStateUsing(fn (float $state): string => number_format($state, 1) . '%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pallets_count')
                    ->label('تعداد پالت')
                    ->counts('pallets')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('وضعیت')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rack_id')
                    ->label('قفسه')
                    ->relationship('rack', 'name'),

                Tables\Filters\SelectFilter::make('allowed_product_type')
                    ->label('نوع کالای مجاز')
                    ->options([
                        'general' => 'عمومی',
                        'hazardous' => 'مواد خطرناک',
                        'auto_parts' => 'قطعات یدکی',
                        'emergency_supplies' => 'تجهیزات امدادی',
                        'fragile' => 'شکننده',
                        'heavy_duty' => 'سنگین',
                        'temperature_sensitive' => 'حساس به دما',
                    ]),

                Tables\Filters\SelectFilter::make('occupancy_status')
                    ->label('وضعیت اشغال')
                    ->options([
                        'empty' => 'خالی',
                        'partial' => 'نیمه‌پر',
                        'full' => 'پر',
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
            'index' => Pages\ListShelfLevels::route('/'),
            'create' => Pages\CreateShelfLevel::route('/create'),
            'view' => Pages\ViewShelfLevel::route('/{record}'),
            'edit' => Pages\EditShelfLevel::route('/{record}/edit'),
        ];
    }
}
