<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyInventoryWidget extends ChartWidget
{
    protected int | string | array $columnSpan = 'half';
    
    public function getHeading(): string
    {
        return 'روند موجودی ماهانه';
    }
    
    protected function getData(): array
    {
        $months = [];
        $totalStock = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            // شبیه‌سازی داده‌های موجودی برای 12 ماه گذشته
            $stock = Item::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('current_stock');
            
            $totalStock[] = $stock ?: rand(50, 200);
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'موجودی کل',
                    'data' => $totalStock,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months,
        ];
    }
    
    protected function getType(): string
    {
        return 'line';
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
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)',
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
