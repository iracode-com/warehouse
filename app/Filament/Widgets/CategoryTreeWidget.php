<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class CategoryTreeWidget extends Widget
{
    protected static string $view = 'filament.widgets.category-tree';
    
    protected int | string | array $columnSpan = 'full';
    
    protected function getViewData(): array
    {
        $categories = Category::with(['children' => function($query) {
                $query->withCount('children')->orderBy('name');
            }])
            ->whereNull('parent_id')
            ->withCount('children')
            ->orderBy('name')
            ->get();
            
        return [
            'categories' => $categories,
        ];
    }
}