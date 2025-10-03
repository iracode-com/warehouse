<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryAttributeResource\Pages;
use App\Models\CategoryAttribute;
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

class CategoryAttributeResource extends Resource
{
    protected static ?string $model = CategoryAttribute::class;

    public static function getNavigationIcon(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return 'فیلدهای دسته‌بندی';
    }

    public static function getModelLabel(): string
    {
        return 'فیلد دسته‌بندی';
    }

    public static function getPluralModelLabel(): string
    {
        return 'فیلدهای دسته‌بندی';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'مدیریت کالا';
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
                Tabs::make('فیلد دسته‌بندی')
                    ->tabs([
                        Tab::make('اطلاعات پایه')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label('دسته‌بندی')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('order_index')
                                    ->label('ترتیب نمایش')
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('name')
                                    ->label('نام فیلد')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('نام فنی فیلد (فقط حروف انگلیسی و اعداد)')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('label')
                                    ->label('برچسب نمایشی')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('نام نمایشی فیلد برای کاربران')
                                    ->columnSpan(1),

                                Forms\Components\Select::make('type')
                                    ->label('نوع فیلد')
                                    ->options(CategoryAttribute::getTypeOptions())
                                    ->required()
                                    ->reactive()
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('help_text')
                                    ->label('متن راهنما')
                                    ->rows(2)
                                    ->helperText('راهنمای استفاده از فیلد برای کاربران')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Tab::make('تنظیمات فیلد')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Textarea::make('default_value')
                                    ->label('مقدار پیش‌فرض')
                                    ->rows(2)
                                    ->helperText('مقدار پیش‌فرض فیلد')
                                    ->columnSpanFull(),

                                Forms\Components\KeyValue::make('options')
                                    ->label('گزینه‌های انتخابی')
                                    ->keyLabel('مقدار')
                                    ->valueLabel('برچسب')
                                    ->helperText('گزینه‌های انتخابی برای فیلدهای select و multiselect (مثال: red => قرمز، blue => آبی)')
                                    ->visible(fn (callable $get): bool => in_array($get('type'), [CategoryAttribute::TYPE_SELECT, CategoryAttribute::TYPE_MULTISELECT]))
                                    ->columnSpanFull()
                                    ->addActionLabel('اضافه کردن گزینه')
                                    ->deleteActionLabel('حذف گزینه')
                                    ->reorderable(),

                                Forms\Components\KeyValue::make('validation_rules')
                                    ->label('قوانین اعتبارسنجی')
                                    ->keyLabel('قانون')
                                    ->valueLabel('مقدار')
                                    ->helperText('قوانین اعتبارسنجی فیلد (مثال: min:1, max:100)')
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('ویژگی‌های فیلد')
                            ->icon('heroicon-o-check-circle')
                            ->schema([
                                Forms\Components\Toggle::make('is_required')
                                    ->label('اجباری')
                                    ->default(false)
                                    ->helperText('آیا این فیلد اجباری است؟')
                                    ->columnSpan(1),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('فعال')
                                    ->default(true)
                                    ->helperText('آیا این فیلد فعال است؟')
                                    ->columnSpan(1),

                                Forms\Components\Toggle::make('is_searchable')
                                    ->label('قابل جستجو')
                                    ->default(false)
                                    ->helperText('آیا می‌توان در این فیلد جستجو کرد؟')
                                    ->columnSpan(1),

                                Forms\Components\Toggle::make('is_filterable')
                                    ->label('قابل فیلتر')
                                    ->default(false)
                                    ->helperText('آیا می‌توان بر اساس این فیلد فیلتر کرد؟')
                                    ->columnSpan(1),
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
                Tables\Columns\TextColumn::make('category.name')
                    ->label('دسته‌بندی')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام فیلد')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('label')
                    ->label('برچسب')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('نوع')
                    ->badge()
                    ->formatStateUsing(fn($state) => (new CategoryAttribute())->setAttribute('type', $state)->type_label)
                    ->color(fn (string $state): string => match ($state) {
                        CategoryAttribute::TYPE_TEXT => 'primary',
                        CategoryAttribute::TYPE_NUMBER => 'success',
                        CategoryAttribute::TYPE_DATE => 'warning',
                        CategoryAttribute::TYPE_BOOLEAN => 'info',
                        CategoryAttribute::TYPE_SELECT => 'secondary',
                        CategoryAttribute::TYPE_MULTISELECT => 'secondary',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('order_index')
                    ->label('ترتیب')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_required')
                    ->label('اجباری')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_searchable')
                    ->label('قابل جستجو')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_filterable')
                    ->label('قابل فیلتر')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),

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

                Tables\Filters\SelectFilter::make('type')
                    ->label('نوع فیلد')
                    ->options([
                        'text' => 'متن',
                        'number' => 'عدد',
                        'date' => 'تاریخ',
                        'datetime' => 'تاریخ و زمان',
                        'boolean' => 'بله/خیر',
                        'select' => 'انتخاب تکی',
                        'multiselect' => 'انتخاب چندگانه',
                        'textarea' => 'متن طولانی',
                        'file' => 'فایل',
                        'image' => 'تصویر',
                    ]),

                Tables\Filters\TernaryFilter::make('is_required')
                    ->label('اجباری'),

                Tables\Filters\TernaryFilter::make('is_searchable')
                    ->label('قابل جستجو'),

                Tables\Filters\TernaryFilter::make('is_filterable')
                    ->label('قابل فیلتر'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('فعال'),
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
            ->defaultSort('order_index');
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
            'index' => Pages\ListCategoryAttributes::route('/'),
            'create' => Pages\CreateCategoryAttribute::route('/create'),
            'view' => Pages\ViewCategoryAttribute::route('/{record}'),
            'edit' => Pages\EditCategoryAttribute::route('/{record}/edit'),
        ];
    }
}
