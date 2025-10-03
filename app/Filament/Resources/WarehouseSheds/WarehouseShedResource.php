<?php

namespace App\Filament\Resources\WarehouseSheds;

use App\Filament\Resources\WarehouseSheds\Pages\CreateWarehouseShed;
use App\Filament\Resources\WarehouseSheds\Pages\EditWarehouseShed;
use App\Filament\Resources\WarehouseSheds\Pages\ListWarehouseSheds;
use App\Filament\Resources\WarehouseSheds\Schemas\WarehouseShedForm;
use App\Filament\Resources\WarehouseSheds\Tables\WarehouseShedsTable;
use App\Models\WarehouseShed;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WarehouseShedResource extends Resource
{
    protected static ?string $model = WarehouseShed::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return 'مدیریت انبار';
    }

    public static function getNavigationLabel(): string
    {
        return 'سوله‌ها';
    }

    public static function getPluralLabel(): ?string
    {
        return 'سوله‌ها';
    }

    public static function getLabel(): ?string
    {
        return 'سوله';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationIcon(): ?string
    {
        return null;
    }

    public static function canAccess(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return WarehouseShedForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WarehouseShedsTable::configure($table);
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
            'index' => ListWarehouseSheds::route('/'),
            'create' => CreateWarehouseShed::route('/create'),
            'edit' => EditWarehouseShed::route('/{record}/edit'),
        ];
    }
}
