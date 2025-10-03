<?php

namespace App\Filament\Resources\Location;

use App\Filament\Resources\Location\CorridorResource\Pages;
use App\Models\Location\Corridor;
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

class CorridorResource extends Resource
{
    protected static ?string $model = Corridor::class;

    public static function getNavigationIcon(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return 'راهروها';
    }

    public static function getModelLabel(): string
    {
        return 'راهرو';
    }

    public static function getPluralModelLabel(): string
    {
        return 'راهروها';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'محل قرارگیری کالا';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function canAccess(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات راهرو')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('کد راهرو')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('name')
                            ->label('نام راهرو')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('zone_id')
                            ->label('منطقه')
                            ->relationship('zone', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('access_type')
                            ->label('نوع دسترسی')
                            ->options(__('common-options.access_type'))
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('توضیحات')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('ابعاد و ظرفیت')
                    ->schema([
                        Forms\Components\TextInput::make('width')
                            ->label('عرض (متر)')
                            ->required()
                            ->numeric()
                            ->step(0.01),

                        Forms\Components\TextInput::make('length')
                            ->label('طول (متر)')
                            ->required()
                            ->numeric()
                            ->step(0.01),

                        Forms\Components\TextInput::make('height')
                            ->label('ارتفاع (متر)')
                            ->numeric()
                            ->step(0.01),

                        Forms\Components\TextInput::make('rack_count')
                            ->label('تعداد قفسه‌ها')
                            ->numeric()
                            ->integer()
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
                    ->label('کد راهرو')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام راهرو')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('zone.name')
                    ->label('منطقه')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('zone.warehouse.title')
                    ->label('انبار')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('access_type_label')
                    ->label('نوع دسترسی')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'پیاده' => 'info',
                        'لیفتراک' => 'warning',
                        'جرثقیل' => 'danger',
                        'مختلط' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('width')
                    ->label('عرض (م)')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('length')
                    ->label('طول (م)')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('area')
                    ->label('مساحت (م²)')
                    ->getStateUsing(fn (Corridor $record): float => $record->area)
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rack_count')
                    ->label('تعداد قفسه')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('racks_count')
                    ->label('قفسه‌های موجود')
                    ->counts('racks')
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
                Tables\Filters\SelectFilter::make('zone_id')
                    ->label('منطقه')
                    ->relationship('zone', 'name'),

                Tables\Filters\SelectFilter::make('access_type')
                    ->label('نوع دسترسی')
                    ->options([
                        'pedestrian' => 'پیاده',
                        'forklift' => 'لیفتراک',
                        'crane' => 'جرثقیل',
                        'mixed' => 'مختلط',
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
            'index' => Pages\ListCorridors::route('/'),
            'create' => Pages\CreateCorridor::route('/create'),
            'view' => Pages\ViewCorridor::route('/{record}'),
            'edit' => Pages\EditCorridor::route('/{record}/edit'),
        ];
    }
}
