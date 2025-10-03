<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class StockLevelWidget extends ChartWidget
{
    protected int | string | array $columnSpan = 'half';
    
    public function getHeading(): string
    {
        return 'وضعیت موجودی اقلام';
    }
    
    protected function getData(): array
    {
        $items = Item::all();
        
        $lowStock = $items->filter(function ($item) {
            return $item->current_stock <= $item->min_stock;
        })->count();
        
        $normalStock = $items->filter(function ($item) {
            return $item->current_stock > $item->min_stock && $item->current_stock < $item->max_stock;
        })->count();
        
        $highStock = $items->filter(function ($item) {
            return $item->current_stock >= $item->max_stock;
        })->count();
        
        return [
            'datasets' => [
                [
                    'label' => 'تعداد اقلام',
                    'data' => [$lowStock, $normalStock, $highStock],
                    'backgroundColor' => [
                        '#EF4444', // قرمز برای موجودی کم
                        '#10B981', // سبز برای موجودی نرمال
                        '#F59E0B', // زرد برای موجودی زیاد
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => ['موجودی کم', 'موجودی نرمال', 'موجودی زیاد'],
        ];
    }
    
    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
