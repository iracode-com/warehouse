<?php

namespace App\Filament\Resources\Location;

use App\Filament\Resources\Location\RackResource\Pages;
use App\Models\Location\Rack;
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

class RackResource extends Resource
{
    protected static ?string $model = Rack::class;

    public static function getNavigationIcon(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return 'قفسه‌ها';
    }

    public static function getModelLabel(): string
    {
        return 'قفسه';
    }

    public static function getPluralModelLabel(): string
    {
        return 'قفسه‌ها';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'محل قرارگیری کالا';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function canAccess(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات قفسه')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('کد قفسه')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('name')
                            ->label('نام قفسه')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('corridor_id')
                            ->label('راهرو')
                            ->relationship('corridor', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('rack_type')
                            ->label('نوع قفسه')
                            ->options([
                                'fixed' => 'ثابت',
                                'movable' => 'متحرک',
                                'pallet_rack' => 'پالت‌دار',
                                'shelving' => 'قفسه‌بندی',
                                'cantilever' => 'کنسول',
                                'drive_in' => 'رانشی',
                                'push_back' => 'فشاری',
                            ])
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

                        Forms\Components\TextInput::make('level_count')
                            ->label('تعداد طبقات')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(1),

                        Forms\Components\TextInput::make('capacity_per_level')
                            ->label('ظرفیت هر طبقه (کیلوگرم)')
                            ->required()
                            ->numeric()
                            ->step(0.01),

                        Forms\Components\TextInput::make('max_weight')
                            ->label('حداکثر وزن قابل تحمل (کیلوگرم)')
                            ->required()
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
                    ->label('کد قفسه')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام قفسه')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('corridor.name')
                    ->label('راهرو')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('corridor.zone.name')
                    ->label('منطقه')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('corridor.zone.warehouse.title')
                    ->label('انبار')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rack_type_label')
                    ->label('نوع قفسه')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ثابت' => 'info',
                        'متحرک' => 'warning',
                        'پالت‌دار' => 'success',
                        'قفسه‌بندی' => 'primary',
                        'کنسول' => 'secondary',
                        'رانشی' => 'danger',
                        'فشاری' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('level_count')
                    ->label('تعداد طبقات')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_capacity')
                    ->label('ظرفیت کل (کگ)')
                    ->getStateUsing(fn (Rack $record): float => $record->total_capacity)
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('max_weight')
                    ->label('حداکثر وزن (کگ)')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('volume')
                    ->label('حجم (م³)')
                    ->getStateUsing(fn (Rack $record): float => $record->volume)
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('shelf_levels_count')
                    ->label('طبقات موجود')
                    ->counts('shelfLevels')
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
                Tables\Filters\SelectFilter::make('corridor_id')
                    ->label('راهرو')
                    ->relationship('corridor', 'name'),

                Tables\Filters\SelectFilter::make('rack_type')
                    ->label('نوع قفسه')
                    ->options([
                        'fixed' => 'ثابت',
                        'movable' => 'متحرک',
                        'pallet_rack' => 'پالت‌دار',
                        'shelving' => 'قفسه‌بندی',
                        'cantilever' => 'کنسول',
                        'drive_in' => 'رانشی',
                        'push_back' => 'فشاری',
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
            'index' => Pages\ListRacks::route('/'),
            'create' => Pages\CreateRack::route('/create'),
            'view' => Pages\ViewRack::route('/{record}'),
            'edit' => Pages\EditRack::route('/{record}/edit'),
        ];
    }
}
