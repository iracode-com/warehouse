<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Item;
use App\Models\ProductProfile;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class InventoryChartWidget extends ChartWidget
{
    protected int | string | array $columnSpan = 'full';
    
    public function getHeading(): string
    {
        return 'نمودار موجودی انبار';
    }
    
    protected function getData(): array
    {
        $categories = Category::withCount(['productProfiles' => function ($query) {
            $query->whereHas('items');
        }])->get();
        
        return [
            'datasets' => [
                [
                    'label' => 'تعداد محصولات',
                    'data' => $categories->pluck('product_profiles_count'),
                    'backgroundColor' => [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6B7280'
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $categories->pluck('name'),
        ];
    }
    
    protected function getType(): string
    {
        return 'doughnut';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 20,
                        'usePointStyle' => true,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
