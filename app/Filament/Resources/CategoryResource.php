<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    public static function getNavigationIcon(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return __('category.navigation.plural');
    }

    public static function getModelLabel(): string
    {
        return __('category.navigation.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('category.navigation.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('category.navigation.group');
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('دسته‌بندی')
                    ->tabs([
                        Tab::make('اطلاعات پایه')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('کد دسته‌بندی')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('name')
                                    ->label('نام دسته‌بندی')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),

                                Forms\Components\Textarea::make('description')
                                    ->label('توضیحات')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Forms\Components\Select::make('parent_id')
                                    ->label('دسته والد')
                                    ->relationship('parent', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $parent = Category::find($state);
                                            $set('hierarchy_level', $parent->hierarchy_level + 1);
                                        } else {
                                            $set('hierarchy_level', 1);
                                        }
                                    })
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('hierarchy_level')
                                    ->label('سطح سلسله‌مراتبی')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('order_index')
                                    ->label('ترتیب نمایش')
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),

                                Forms\Components\Select::make('status')
                                    ->label('وضعیت')
                                    ->options(Category::getStatusOptions())
                                    ->default(Category::STATUS_ACTIVE)
                                    ->required()
                                    ->columnSpan(1),

                                Forms\Components\Select::make('category_type')
                                    ->label('نوع دسته‌بندی')
                                    ->options(__('product-profile.options.category_types'))
                                    ->searchable()
                                    ->columnSpan(1),

                                Forms\Components\Toggle::make('is_leaf')
                                    ->label('دسته نهایی (کالا)')
                                    ->default(false)
                                    ->helperText('آیا این دسته‌بندی نهایی است و کالاها در آن قرار می‌گیرند؟')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Tab::make('مشخصه‌های فنی')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\KeyValue::make('metadata')
                                    ->label('مشخصه‌های فنی')
                                    ->keyLabel('کلید')
                                    ->valueLabel('مقدار')
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
                Tables\Columns\TextColumn::make('code')
                    ->label('کد')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('والد')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('hierarchy_level_label')
                    ->label('سطح')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'دسته اصلی' => 'primary',
                        'زیر دسته' => 'success',
                        'زیر زیر دسته' => 'warning',
                        'کالا' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('order_index')
                    ->label('ترتیب')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('children_count')
                    ->label('زیردسته‌ها')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('کالاها')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category_type')
                    ->label('نوع دسته‌بندی')
                    ->getStateUsing(function ($record) {
                        return $record->category_type ? __('product-profile.options.category_types.' . $record->category_type) : '';
                    })
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn($state) => (new Category())->setAttribute('status', $state)->status_label)
                    ->color(fn(string $state): string => match ($state) {
                        Category::STATUS_ACTIVE => 'success',
                        Category::STATUS_INACTIVE => 'warning',
                        Category::STATUS_ARCHIVED => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_leaf')
                    ->label('نهایی')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('دسته والد')
                    ->relationship('parent', 'name'),

                Tables\Filters\SelectFilter::make('hierarchy_level')
                    ->label('سطح سلسله‌مراتبی')
                    ->options(__('common-options.hierarchical_level')),

                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options(__('common-options.status')),

                Tables\Filters\SelectFilter::make('category_type')
                    ->label('نوع دسته‌بندی')
                    ->options(__('product-profile.options.category_types')),

                Tables\Filters\TernaryFilter::make('is_leaf')
                    ->label('دسته نهایی'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
