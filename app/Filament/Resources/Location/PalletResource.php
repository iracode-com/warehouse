<?php

namespace App\Filament\Resources\Location;

use App\Filament\Resources\Location\PalletResource\Pages;
use App\Models\Location\Pallet;
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

class PalletResource extends Resource
{
    protected static ?string $model = Pallet::class;

    public static function getNavigationIcon(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return 'پالت‌ها';
    }

    public static function getModelLabel(): string
    {
        return 'پالت';
    }

    public static function getPluralModelLabel(): string
    {
        return 'پالت‌ها';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'محل قرارگیری کالا';
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    public static function canAccess(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات پالت')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('کد پالت')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('name')
                            ->label('نام پالت')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('pallet_type')
                            ->label('نوع پالت')
                            ->options([
                                'small' => 'کوچک',
                                'large' => 'بزرگ',
                                'standard' => 'استاندارد',
                                'euro_pallet' => 'پالت اروپایی',
                                'american_pallet' => 'پالت آمریکایی',
                                'custom' => 'سفارشی',
                            ])
                            ->required(),

                        Forms\Components\Select::make('shelf_level_id')
                            ->label('طبقه فعلی')
                            ->relationship('shelfLevel', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('current_position')
                            ->label('موقعیت فعلی')
                            ->maxLength(255),

                        Forms\Components\Select::make('status')
                            ->label('وضعیت')
                            ->options([
                                'available' => 'در دسترس',
                                'occupied' => 'اشغال شده',
                                'maintenance' => 'تعمیر',
                                'damaged' => 'آسیب دیده',
                            ])
                            ->default('available')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('توضیحات')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('ابعاد و وزن')
                    ->schema([
                        Forms\Components\TextInput::make('length')
                            ->label('طول (متر)')
                            ->required()
                            ->numeric()
                            ->step(0.01),

                        Forms\Components\TextInput::make('width')
                            ->label('عرض (متر)')
                            ->required()
                            ->numeric()
                            ->step(0.01),

                        Forms\Components\TextInput::make('height')
                            ->label('ارتفاع (متر)')
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
                    ->label('کد پالت')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام پالت')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pallet_type_label')
                    ->label('نوع پالت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'کوچک' => 'info',
                        'بزرگ' => 'warning',
                        'استاندارد' => 'success',
                        'پالت اروپایی' => 'primary',
                        'پالت آمریکایی' => 'secondary',
                        'سفارشی' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('shelfLevel.name')
                    ->label('طبقه فعلی')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('current_position')
                    ->label('موقعیت فعلی')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_label')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'در دسترس' => 'success',
                        'اشغال شده' => 'warning',
                        'تعمیر' => 'info',
                        'آسیب دیده' => 'danger',
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
                    ->getStateUsing(fn (Pallet $record): float => $record->available_weight)
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('occupancy_percentage')
                    ->label('درصد اشغال')
                    ->getStateUsing(fn (Pallet $record): float => $record->occupancy_percentage)
                    ->numeric()
                    ->formatStateUsing(fn (float $state): string => number_format($state, 1) . '%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('volume')
                    ->label('حجم (م³)')
                    ->getStateUsing(fn (Pallet $record): float => $record->volume)
                    ->numeric()
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
                Tables\Filters\SelectFilter::make('shelf_level_id')
                    ->label('طبقه')
                    ->relationship('shelfLevel', 'name'),

                Tables\Filters\SelectFilter::make('pallet_type')
                    ->label('نوع پالت')
                    ->options([
                        'small' => 'کوچک',
                        'large' => 'بزرگ',
                        'standard' => 'استاندارد',
                        'euro_pallet' => 'پالت اروپایی',
                        'american_pallet' => 'پالت آمریکایی',
                        'custom' => 'سفارشی',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'available' => 'در دسترس',
                        'occupied' => 'اشغال شده',
                        'maintenance' => 'تعمیر',
                        'damaged' => 'آسیب دیده',
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
            'index' => Pages\ListPallets::route('/'),
            'create' => Pages\CreatePallet::route('/create'),
            'view' => Pages\ViewPallet::route('/{record}'),
            'edit' => Pages\EditPallet::route('/{record}/edit'),
        ];
    }
}
