<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackagingTypeResource\Pages;
use App\Models\PackagingType;
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

class PackagingTypeResource extends Resource
{
    protected static ?string $model = PackagingType::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-cube';
    }

    public static function getNavigationLabel(): string
    {
        return 'انواع بسته‌بندی';
    }

    public static function getModelLabel(): string
    {
        return 'نوع بسته‌بندی';
    }

    public static function getPluralModelLabel(): string
    {
        return 'انواع بسته‌بندی';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'اطلاعات پایه';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات نوع بسته‌بندی')
                    ->description('اطلاعات اساسی نوع بسته‌بندی')
                    ->icon('heroicon-o-cube')
                    ->iconColor('primary')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('نام نوع بسته‌بندی')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),

                        // Forms\Components\TextInput::make('code')
                        //     ->label('کد نوع بسته‌بندی')
                        //     ->required()
                        //     ->unique(ignoreRecord: true)
                        //     ->maxLength(255)
                        //     ->columnSpan(1),

                        // Forms\Components\TextInput::make('unit')
                        //     ->label('واحد اندازه‌گیری')
                        //     ->maxLength(255)
                        //     ->columnSpan(1),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('ترتیب نمایش')
                            ->numeric()
                            ->default(0)
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label('فعال')
                            ->default(true),

                        Forms\Components\Textarea::make('description')
                            ->label('توضیحات')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('code')
                //     ->label('کد')
                //     ->searchable()
                //     ->sortable()
                //     ->weight('bold'),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                // Tables\Columns\TextColumn::make('unit')
                //     ->label('واحد')
                //     ->searchable()
                //     ->toggleable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('ترتیب')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('وضعیت')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('product_profiles_count')
                    ->label('تعداد کالاها')
                    ->counts('productProfiles')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('وضعیت')
                    ->boolean()
                    ->trueLabel('فعال')
                    ->falseLabel('غیرفعال')
                    ->native(false),
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
            ->defaultSort('sort_order');
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
            'index' => Pages\ListPackagingTypes::route('/'),
            'create' => Pages\CreatePackagingType::route('/create'),
            'view' => Pages\ViewPackagingType::route('/{record}'),
            'edit' => Pages\EditPackagingType::route('/{record}/edit'),
        ];
    }
}
