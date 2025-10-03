<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    // protected static ?string $navigationIcon = Heroicon::Home;
    
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\DiamondNavigationWidget::class,
            // \App\Filament\Widgets\StatsOverviewWidget::class,
            // \App\Filament\Widgets\InventoryChartWidget::class,
            // \App\Filament\Widgets\StockLevelWidget::class,
            // \App\Filament\Widgets\MonthlyInventoryWidget::class,
            // \App\Filament\Widgets\RecentItemsWidget::class,
            // \App\Filament\Widgets\WarehouseStatusWidget::class,
            // \App\Filament\Widgets\LowStockAlertWidget::class,
        ];
    }
}
