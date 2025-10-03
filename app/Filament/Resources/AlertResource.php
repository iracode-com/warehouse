<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlertResource\Pages;
use App\Models\Alert;
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
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class AlertResource extends Resource
{
    protected static ?string $model = Alert::class;

    public static function getNavigationIcon(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return 'هشدارها';
    }

    public static function getModelLabel(): string
    {
        return 'هشدار';
    }

    public static function getPluralModelLabel(): string
    {
        return 'هشدارها';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'مدیریت کالا';
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
                Tabs::make('هشدار')
                    ->tabs([
                        Tab::make('اطلاعات هشدار')
                            ->icon('heroicon-o-exclamation-triangle')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('عنوان هشدار')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('عنوان کوتاه و واضح هشدار')
                                    ->columnSpan(1),

                                Forms\Components\Select::make('alert_type')
                                    ->label('نوع هشدار')
                                    ->options(Alert::getAlertTypeOptions())
                                    ->required()
                                    ->helperText('سطح اهمیت هشدار')
                                    ->columnSpan(1),

                                Forms\Components\Textarea::make('message')
                                    ->label('پیام هشدار')
                                    ->required()
                                    ->rows(3)
                                    ->helperText('پیام کامل و توضیحی هشدار')
                                    ->columnSpanFull(),

                                Forms\Components\Select::make('status')
                                    ->label('وضعیت')
                                    ->options(Alert::getStatusOptions())
                                    ->required()
                                    ->helperText('وضعیت فعلی هشدار')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('priority')
                                    ->label('اولویت')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(10)
                                    ->default(1)
                                    ->helperText('اولویت هشدار (1=کم، 10=بحرانی)')
                                    ->columnSpan(1),

                                Forms\Components\Toggle::make('is_read')
                                    ->label('خوانده شده')
                                    ->default(false)
                                    ->helperText('آیا هشدار خوانده شده است؟')
                                    ->columnSpan(1),
                            ])
                            ->columns(2),

                        Tab::make('مراجع')
                            ->icon('heroicon-o-link')
                            ->schema([
                                Forms\Components\Select::make('rule_id')
                                    ->label('قانون')
                                    ->relationship('rule', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('قانونی که این هشدار را ایجاد کرده')
                                    ->columnSpan(1),

                                Forms\Components\Select::make('category_id')
                                    ->label('دسته‌بندی')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('دسته‌بندی مربوط به هشدار')
                                    ->columnSpan(1),

                                Forms\Components\Select::make('attribute_id')
                                    ->label('فیلد')
                                    ->relationship('attribute', 'label')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('فیلد خاص مربوط به هشدار')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Tab::make('گیرندگان و زمان‌ها')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Forms\Components\KeyValue::make('recipients')
                                    ->label('گیرندگان')
                                    ->keyLabel('نوع')
                                    ->valueLabel('مقدار')
                                    ->helperText('لیست گیرندگان هشدار')
                                    ->columnSpanFull(),

                                Forms\Components\DateTimePicker::make('sent_at')
                                    ->label('زمان ارسال')
                                    ->helperText('زمان ارسال هشدار')
                                    ->columnSpan(1),

                                Forms\Components\DateTimePicker::make('acknowledged_at')
                                    ->label('زمان تایید')
                                    ->helperText('زمان تایید هشدار')
                                    ->columnSpan(1),

                                Forms\Components\DateTimePicker::make('resolved_at')
                                    ->label('زمان حل')
                                    ->helperText('زمان حل هشدار')
                                    ->columnSpan(1),

                                Forms\Components\Select::make('acknowledged_by')
                                    ->label('تایید کننده')
                                    ->relationship('acknowledgedBy', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('کاربری که هشدار را تایید کرده')
                                    ->columnSpan(1),

                                Forms\Components\Select::make('resolved_by')
                                    ->label('حل کننده')
                                    ->relationship('resolvedBy', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('کاربری که هشدار را حل کرده')
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('resolution_notes')
                                    ->label('یادداشت‌های حل')
                                    ->rows(3)
                                    ->helperText('توضیحات و یادداشت‌های مربوط به حل هشدار')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Tab::make('اطلاعات اضافی')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\KeyValue::make('trigger_data')
                                    ->label('داده‌های فعال‌سازی')
                                    ->keyLabel('کلید')
                                    ->valueLabel('مقدار')
                                    ->helperText('داده‌های مربوط به فعال‌سازی هشدار')
                                    ->columnSpanFull(),

                                Forms\Components\Placeholder::make('alert_timeline')
                                    ->label('زمان‌بندی هشدار')
                                    ->content('زمان‌بندی کامل هشدار در اینجا نمایش داده می‌شود')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('rule.name')
                    ->label('قانون')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('دسته‌بندی')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('alert_type')
                    ->label('نوع')
                    ->badge()
                    ->formatStateUsing(fn($state) => (new Alert())->setAttribute('alert_type', $state)->alert_type_label)
                    ->color(fn (string $state): string => match ($state) {
                        Alert::ALERT_TYPE_INFO => 'blue',
                        Alert::ALERT_TYPE_WARNING => 'yellow',
                        Alert::ALERT_TYPE_ERROR => 'orange',
                        Alert::ALERT_TYPE_CRITICAL => 'red',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn($state) => (new Alert())->setAttribute('status', $state)->status_label)
                    ->color(fn (string $state): string => match ($state) {
                        Alert::STATUS_PENDING => 'gray',
                        Alert::STATUS_SENT => 'blue',
                        Alert::STATUS_ACKNOWLEDGED => 'yellow',
                        Alert::STATUS_RESOLVED => 'green',
                        Alert::STATUS_DISMISSED => 'red',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('priority_label')
                    ->label('اولویت')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        1, 2, 3 => 'gray',
                        4, 5, 6 => 'yellow',
                        7, 8, 9, 10 => 'red',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_read')
                    ->label('خوانده شده')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->jalaliDateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sent_at')
                    ->label('زمان ارسال')
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('acknowledged_at')
                    ->label('زمان تایید')
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('resolved_at')
                    ->label('زمان حل')
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rule_id')
                    ->label('قانون')
                    ->relationship('rule', 'name'),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('دسته‌بندی')
                    ->relationship('category', 'name'),

                Tables\Filters\SelectFilter::make('alert_type')
                    ->label('نوع هشدار')
                    ->options([
                        'info' => 'اطلاعاتی',
                        'warning' => 'هشدار',
                        'error' => 'خطا',
                        'critical' => 'بحرانی',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending' => 'در انتظار',
                        'sent' => 'ارسال شده',
                        'acknowledged' => 'تایید شده',
                        'resolved' => 'حل شده',
                        'dismissed' => 'رد شده',
                    ]),

                Tables\Filters\SelectFilter::make('priority')
                    ->label('اولویت')
                    ->options([
                        1 => 'خیلی کم',
                        2 => 'کم',
                        3 => 'متوسط',
                        4 => 'بالا',
                        5 => 'خیلی بالا',
                        6 => 'فوری',
                        7 => 'بحرانی',
                        8 => 'اضطراری',
                        9 => 'فوق‌العاده',
                        10 => 'بحرانی مطلق',
                    ]),

                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('خوانده شده'),

                Tables\Filters\Filter::make('active')
                    ->label('فعال')
                    ->query(fn ($query) => $query->whereIn('status', ['pending', 'sent', 'acknowledged'])),

                Tables\Filters\Filter::make('closed')
                    ->label('بسته شده')
                    ->query(fn ($query) => $query->whereIn('status', ['resolved', 'dismissed'])),

                Tables\Filters\Filter::make('high_priority')
                    ->label('اولویت بالا')
                    ->query(fn ($query) => $query->where('priority', '>=', 7)),

                Tables\Filters\Filter::make('critical')
                    ->label('بحرانی')
                    ->query(fn ($query) => $query->where('priority', '>=', 9)),
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
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListAlerts::route('/'),
            'view' => Pages\ViewAlert::route('/{record}'),
            'edit' => Pages\EditAlert::route('/{record}/edit'),
        ];
    }
}
