<?php

namespace App\Filament\Resources\Location;

use App\Filament\Resources\Location\RackInspectionResource\Pages;
use App\Models\Location\RackInspection;
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

class RackInspectionResource extends Resource
{
    protected static ?string $model = RackInspection::class;

    public static function getNavigationIcon(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return 'بازرسی قفسه‌ها';
    }

    public static function getModelLabel(): string
    {
        return 'بازرسی قفسه';
    }

    public static function getPluralModelLabel(): string
    {
        return 'بازرسی قفسه‌ها';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'محل قرارگیری کالا';
    }

    public static function getNavigationSort(): ?int
    {
        return 6;
    }

    public static function canAccess(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات بازرسی')
                    ->schema([
                        Forms\Components\Select::make('rack_id')
                            ->label('قفسه')
                            ->relationship('rack', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('inspector_id')
                            ->label('بازرس')
                            ->relationship('inspector', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\DatePicker::make('inspection_date')
                            ->label('تاریخ بازرسی')
                            ->required()
                            ->jalali()
                            ->default(now()),

                        Forms\Components\Select::make('safety_status')
                            ->label('وضعیت ایمنی')
                            ->options([
                                'standard' => 'استاندارد',
                                'needs_repair' => 'نیاز به تعمیر',
                                'critical' => 'بحرانی',
                                'out_of_service' => 'خارج از سرویس',
                            ])
                            ->required(),

                        Forms\Components\DatePicker::make('next_inspection_date')
                            ->jalali()
                            ->label('تاریخ بازرسی بعدی'),

                        Forms\Components\Toggle::make('requires_maintenance')
                            ->label('نیاز به تعمیر'),
                    ])
                    ->columns(2),

                Section::make('جزئیات بازرسی')
                    ->schema([
                        Forms\Components\Textarea::make('inspection_notes')
                            ->label('یادداشت‌های بازرسی')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\Repeater::make('issues_found')
                            ->label('مشکلات یافت شده')
                            ->schema([
                                Forms\Components\TextInput::make('issue')
                                    ->label('مشکل')
                                    ->required(),
                                Forms\Components\Select::make('severity')
                                    ->label('شدت')
                                    ->options([
                                        'low' => 'کم',
                                        'medium' => 'متوسط',
                                        'high' => 'زیاد',
                                        'critical' => 'بحرانی',
                                    ])
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->label('توضیحات')
                                    ->rows(2),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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

                Tables\Columns\TextColumn::make('inspector.name')
                    ->label('بازرس')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('inspection_date')
                    ->label('تاریخ بازرسی')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('safety_status_label')
                    ->label('وضعیت ایمنی')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'استاندارد' => 'success',
                        'نیاز به تعمیر' => 'warning',
                        'بحرانی' => 'danger',
                        'خارج از سرویس' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('issues_count')
                    ->label('تعداد مشکلات')
                    ->getStateUsing(fn (RackInspection $record): int => $record->issues_count)
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('requires_maintenance')
                    ->label('نیاز به تعمیر')
                    ->boolean(),

                Tables\Columns\TextColumn::make('next_inspection_date')
                    ->label('بازرسی بعدی')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('days_since_inspection')
                    ->label('روز از بازرسی')
                    ->getStateUsing(fn (RackInspection $record): int => $record->days_since_inspection)
                    ->numeric()
                    ->sortable(),

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

                Tables\Filters\SelectFilter::make('inspector_id')
                    ->label('بازرس')
                    ->relationship('inspector', 'name'),

                Tables\Filters\SelectFilter::make('safety_status')
                    ->label('وضعیت ایمنی')
                    ->options([
                        'standard' => 'استاندارد',
                        'needs_repair' => 'نیاز به تعمیر',
                        'critical' => 'بحرانی',
                        'out_of_service' => 'خارج از سرویس',
                    ]),

                Tables\Filters\TernaryFilter::make('requires_maintenance')
                    ->label('نیاز به تعمیر'),

                Tables\Filters\Filter::make('overdue')
                    ->label('بازرسی‌های منقضی شده')
                    ->query(fn ($query) => $query->where('next_inspection_date', '<', now())),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('inspection_date', 'desc');
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
            'index' => Pages\ListRackInspections::route('/'),
            'create' => Pages\CreateRackInspection::route('/create'),
            'view' => Pages\ViewRackInspection::route('/{record}'),
            'edit' => Pages\EditRackInspection::route('/{record}/edit'),
        ];
    }
}
