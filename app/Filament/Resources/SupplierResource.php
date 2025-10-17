<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use App\Models\Location\City;
use App\Models\Location\Province;
use App\Models\Location\Country;
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

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    public static function getNavigationGroup(): ?string
    {
        return __('warehouse.navigation_groups.user_management');
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-building-office';
    }

    public static function getNavigationLabel(): string
    {
        return 'تامین‌کنندگان';
    }

    public static function getModelLabel(): string
    {
        return 'تامین‌کننده';
    }

    public static function getPluralModelLabel(): string
    {
        return 'تامین‌کنندگان';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات کلی')
                    ->description('اطلاعات اصلی تامین‌کننده')
                    ->icon('heroicon-o-building-office')
                    ->iconColor('primary')
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('کد تامین‌کننده')
                            ->default(fn() => 'SUP-' . \Morilog\Jalali\Jalalian::forge('today')->format('Ymd') . '-' . (now()->hour * 3600 + now()->minute * 60 + now()->second) . '-' . rand(1000, 9999))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\Select::make('entity_type')
                            ->label('نوع شخصیت')
                            ->options(Supplier::getEntityTypeOptions())
                            ->default(Supplier::ENTITY_INDIVIDUAL)
                            ->required()
                            ->live()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('name')
                            ->label('نام تامین‌کننده')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('company_name')
                            ->label('نام شرکت')
                            ->maxLength(255)
                            ->visible(fn($get) => $get('entity_type') === Supplier::ENTITY_LEGAL)
                            ->columnSpan(2),

                        Forms\Components\Select::make('status')
                            ->label('وضعیت')
                            ->options(Supplier::getStatusOptions())
                            ->default(Supplier::STATUS_ACTIVE)
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('notes')
                            ->label('یادداشت‌ها')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('اطلاعات تماس')
                    ->description('اطلاعات تماس و آدرس')
                    ->icon('heroicon-o-phone')
                    ->iconColor('info')
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label('ایمیل')
                            ->email()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('phone')
                            ->label('تلفن')
                            ->tel()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('mobile')
                            ->label('موبایل')
                            ->tel()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('address')
                            ->label('آدرس')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('country_id')
                            ->label('کشور')
                            ->relationship('country', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->columnSpan(1),

                        Forms\Components\Select::make('province_id')
                            ->label('استان')
                            ->relationship('province', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->columnSpan(1),

                        Forms\Components\Select::make('city_id')
                            ->label('شهرستان')
                            ->relationship('city', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('postal_code')
                            ->label('کد پستی')
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('website')
                            ->label('وب‌سایت')
                            ->url()
                            ->maxLength(255)
                            ->columnSpan(2),
                    ]),
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

                Tables\Columns\TextColumn::make('entity_type')
                    ->label('نوع شخصیت')
                    ->badge()
                    ->formatStateUsing(fn($state) => (new Supplier())->setAttribute('entity_type', $state)->getEntityTypeOptions()[$state] ?? $state)
                    ->color(fn(string $state): string => match ($state) {
                        Supplier::ENTITY_INDIVIDUAL => 'info',
                        Supplier::ENTITY_LEGAL => 'success',
                        default => 'gray',
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('company_name')
                    ->label('شرکت')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('contact_person')
                    ->label('شخص تماس')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('تلفن')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('country.name')
                    ->label('کشور')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('province.name')
                    ->label('استان')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('city.name')
                    ->label('شهر')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn($state) => (new Supplier())->setAttribute('status', $state)->status_label)
                    ->color(fn(string $state): string => match ($state) {
                        Supplier::STATUS_ACTIVE => 'success',
                        Supplier::STATUS_INACTIVE => 'warning',
                        Supplier::STATUS_SUSPENDED => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->jalaliDateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options(Supplier::getStatusOptions()),

                Tables\Filters\SelectFilter::make('entity_type')
                    ->label('نوع شخصیت')
                    ->options(Supplier::getEntityTypeOptions()),

                Tables\Filters\SelectFilter::make('country_id')
                    ->label('کشور')
                    ->relationship('country', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('province_id')
                    ->label('استان')
                    ->relationship('province', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('city_id')
                    ->label('شهر')
                    ->relationship('city', 'name')
                    ->searchable()
                    ->preload(),
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
            ->defaultSort('name');
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'view' => Pages\ViewSupplier::route('/{record}'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
