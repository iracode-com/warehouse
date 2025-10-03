<?php

namespace App\Filament\Widgets;

use App\Models\ProductProfile;
use App\Models\Item;
use App\Models\Personnel\Personnel;
use App\Models\Warehouse;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    
    protected function getStats(): array
    {
        return [
            Stat::make(__('dashboard.stats.total_products'), ProductProfile::count())
                ->description(__('dashboard.stats.total_products_desc'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
                
            Stat::make(__('dashboard.stats.total_items'), Item::count())
                ->description(__('dashboard.stats.total_items_desc'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
                
            Stat::make(__('dashboard.stats.total_personnel'), Personnel::count())
                ->description(__('dashboard.stats.total_personnel_desc'))
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
                
            Stat::make(__('dashboard.stats.total_warehouses'), Warehouse::count())
                ->description(__('dashboard.stats.total_warehouses_desc'))
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),
        ];
    }
}
