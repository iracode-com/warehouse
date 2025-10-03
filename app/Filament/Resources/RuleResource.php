<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RuleResource\Pages;
use App\Models\Rule;
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

class RuleResource extends Resource
{
    protected static ?string $model = Rule::class;

    public static function getNavigationIcon(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return 'قوانین هشدار';
    }

    public static function getModelLabel(): string
    {
        return 'قانون';
    }

    public static function getPluralModelLabel(): string
    {
        return 'قوانین';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'مدیریت کالا';
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
                Tabs::make('قانون هشدار')
                    ->tabs([
                        Tab::make('اطلاعات پایه')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('نام قانون')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('نام منحصربه‌فرد قانون')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('priority')
                                    ->label('اولویت')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(10)
                                    ->default(1)
                                    ->helperText('اولویت قانون (1=کم، 10=بحرانی)')
                                    ->columnSpan(1),

                                Forms\Components\Textarea::make('description')
                                    ->label('توضیحات')
                                    ->rows(3)
                                    ->helperText('شرح کامل قانون و هدف آن')
                                    ->columnSpanFull(),

                                Forms\Components\Select::make('category_id')
                                    ->label('دسته‌بندی هدف')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('دسته‌بندی که قانون روی آن اعمال می‌شود')
                                    ->columnSpan(1),

                                Forms\Components\Select::make('attribute_id')
                                    ->label('فیلد هدف')
                                    ->relationship('attribute', 'label')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('فیلد خاص که قانون روی آن اعمال می‌شود')
                                    ->columnSpan(1),
                            ])
                            ->columns(2),

                        Tab::make('شرایط قانون')
                            ->icon('heroicon-o-exclamation-triangle')
                            ->schema([
                                Forms\Components\Select::make('rule_type')
                                    ->label('نوع قانون')
                                    ->options(Rule::getRuleTypeOptions())
                                    ->required()
                                    ->reactive()
                                    ->helperText('نوع داده‌ای که قانون روی آن اعمال می‌شود')
                                    ->columnSpan(1),

                                Forms\Components\Select::make('condition_type')
                                    ->label('نوع شرط')
                                    ->options(Rule::getConditionTypeOptions())
                                    ->required()
                                    ->helperText('نوع شرطی که باید بررسی شود')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('condition_value')
                                    ->label('مقدار شرط')
                                    ->helperText('مقدار یا مقدارهای شرط')
                                    ->visible(fn (callable $get): bool => !in_array($get('condition_type'), ['in', 'not_in', 'between', 'not_between', 'is_null', 'is_not_null']))
                                    ->columnSpanFull(),

                                Forms\Components\KeyValue::make('condition_values')
                                    ->label('مقادیر شرط')
                                    ->keyLabel('مقدار')
                                    ->valueLabel('برچسب')
                                    ->helperText('لیست مقادیر برای شرط‌های چندگانه')
                                    ->visible(fn (callable $get): bool => in_array($get('condition_type'), ['in', 'not_in', 'between', 'not_between']))
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Tab::make('تنظیمات هشدار')
                            ->icon('heroicon-o-bell')
                            ->schema([
                                Forms\Components\Select::make('alert_type')
                                    ->label('نوع هشدار')
                                    ->options(Rule::getAlertTypeOptions())
                                    ->required()
                                    ->default(Rule::ALERT_TYPE_WARNING)
                                    ->helperText('سطح اهمیت هشدار')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('alert_title')
                                    ->label('عنوان هشدار')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('عنوان کوتاه هشدار')
                                    ->columnSpan(1),

                                Forms\Components\Textarea::make('alert_message')
                                    ->label('پیام هشدار')
                                    ->required()
                                    ->rows(3)
                                    ->helperText('پیام کامل هشدار (می‌توانید از {category_name} استفاده کنید)')
                                    ->columnSpanFull(),

                                Forms\Components\KeyValue::make('alert_recipients')
                                    ->label('گیرندگان هشدار')
                                    ->keyLabel('نوع')
                                    ->valueLabel('مقدار')
                                    ->helperText('لیست گیرندگان هشدار (ایمیل، شماره تلفن، و غیره)')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Tab::make('تنظیمات اجرا')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('فعال')
                                    ->default(true)
                                    ->helperText('آیا این قانون فعال است؟')
                                    ->columnSpan(1),

                                Forms\Components\Toggle::make('is_realtime')
                                    ->label('زمان واقعی')
                                    ->default(false)
                                    ->helperText('آیا قانون در زمان واقعی اجرا شود؟')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('check_interval')
                                    ->label('فاصله چک (ثانیه)')
                                    ->numeric()
                                    ->default(3600)
                                    ->helperText('فاصله زمانی بین اجرای قانون (فقط برای قوانین غیرزمان واقعی)')
                                    ->visible(fn (callable $get): bool => !$get('is_realtime'))
                                    ->columnSpanFull(),

                                Forms\Components\KeyValue::make('metadata')
                                    ->label('اطلاعات اضافی')
                                    ->keyLabel('کلید')
                                    ->valueLabel('مقدار')
                                    ->helperText('اطلاعات اضافی برای قانون (JSON)')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام قانون')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('دسته‌بندی')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('attribute.label')
                    ->label('فیلد')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rule_type_label')
                    ->label('نوع قانون')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'عددی' => 'success',
                        'تاریخ' => 'warning',
                        'متنی' => 'primary',
                        'بولی' => 'info',
                        'JSON' => 'secondary',
                        'سفارشی' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('condition_type_label')
                    ->label('نوع شرط')
                    ->badge(),

                Tables\Columns\TextColumn::make('alert_type')
                    ->label('نوع هشدار')
                    ->badge()
                    ->formatStateUsing(fn($state) => (new Rule())->setAttribute('alert_type', $state)->alert_type_label)
                    ->color(fn (string $state): string => match ($state) {
                        Rule::ALERT_TYPE_INFO => 'blue',
                        Rule::ALERT_TYPE_WARNING => 'yellow',
                        Rule::ALERT_TYPE_ERROR => 'orange',
                        Rule::ALERT_TYPE_CRITICAL => 'red',
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

                Tables\Columns\TextColumn::make('trigger_count')
                    ->label('تعداد فعال‌سازی')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_realtime')
                    ->label('زمان واقعی')
                    ->boolean(),

                Tables\Columns\TextColumn::make('last_checked')
                    ->label('آخرین چک')
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('دسته‌بندی')
                    ->relationship('category', 'name'),

                Tables\Filters\SelectFilter::make('rule_type')
                    ->label('نوع قانون')
                    ->options([
                        'numeric' => 'عددی',
                        'date' => 'تاریخ',
                        'string' => 'متنی',
                        'boolean' => 'بولی',
                        'json' => 'JSON',
                        'custom' => 'سفارشی',
                    ]),

                Tables\Filters\SelectFilter::make('alert_type')
                    ->label('نوع هشدار')
                    ->options([
                        'info' => 'اطلاعاتی',
                        'warning' => 'هشدار',
                        'error' => 'خطا',
                        'critical' => 'بحرانی',
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

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('فعال'),

                Tables\Filters\TernaryFilter::make('is_realtime')
                    ->label('زمان واقعی'),
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
            ->defaultSort('priority', 'desc');
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
            'index' => Pages\ListRules::route('/'),
            'create' => Pages\CreateRule::route('/create'),
            'view' => Pages\ViewRule::route('/{record}'),
            'edit' => Pages\EditRule::route('/{record}/edit'),
        ];
    }
}
