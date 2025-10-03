<?php

namespace App\Filament\Resources\WarehouseManagers;

use App\Filament\Resources\WarehouseManagers\Pages\CreateWarehouseManager;
use App\Filament\Resources\WarehouseManagers\Pages\EditWarehouseManager;
use App\Filament\Resources\WarehouseManagers\Pages\ListWarehouseManagers;
use App\Filament\Resources\WarehouseManagers\Schemas\WarehouseManagerForm;
use App\Filament\Resources\WarehouseManagers\Tables\WarehouseManagersTable;
use App\Models\WarehouseManager;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WarehouseManagerResource extends Resource
{
    protected static ?string $model = WarehouseManager::class;

    protected static ?string $navigationLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?string $pluralModelLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('navigation.warehouse_managers');
    }

    public static function getModelLabel(): string
    {
        return __('navigation.warehouse_manager');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.warehouse_managers');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('warehouse.navigation_groups.warehouse_management');
    }

    public static function canAccess(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return WarehouseManagerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WarehouseManagersTable::configure($table);
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
            'index' => ListWarehouseManagers::route('/'),
            'create' => CreateWarehouseManager::route('/create'),
            'view' => \App\Filament\Resources\WarehouseManagers\Pages\ViewWarehouseManager::route('/{record}'),
            'edit' => EditWarehouseManager::route('/{record}/edit'),
        ];
    }
}
