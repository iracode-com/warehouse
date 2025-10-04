<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
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

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-tag';
    }

    public static function getNavigationLabel(): string
    {
        return 'برندها';
    }

    public static function getModelLabel(): string
    {
        return 'برند';
    }

    public static function getPluralModelLabel(): string
    {
        return 'برندها';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'اطلاعات پایه';
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات برند')
                    ->description('اطلاعات برند')
                    ->icon('heroicon-o-tag')
                    ->iconColor('primary')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('نام برند')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),

                        // Forms\Components\TextInput::make('code')
                        //     ->label('کد برند')
                        //     ->required()
                        //     ->unique(ignoreRecord: true)
                        //     ->maxLength(255)
                        //     ->columnSpan(1),

                        Forms\Components\TextInput::make('phone')
                            ->label('شماره تماس')
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('email')
                            ->label('ایمیل')
                            ->email()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('address')
                            ->label('آدرس')
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('website')
                            ->label('وب‌سایت')
                            ->url()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('ترتیب نمایش')
                            ->numeric()
                            ->default(0)
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label('فعال')
                            ->default(true)
                            ->columnSpan(1),

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

                Tables\Columns\TextColumn::make('phone')
                    ->label('شماره تماس')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('ایمیل')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('address')
                    ->label('آدرس')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('website')
                    ->label('وب‌سایت')
                    ->url(fn($record) => $record->website)
                    ->openUrlInNewTab()
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'view' => Pages\ViewBrand::route('/{record}'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
