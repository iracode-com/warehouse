<?php

namespace App\Filament\Widgets;

use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class DiamondNavigationWidget extends Widget
{
    protected static bool $isLazy = false;
    protected string $view = 'filament.widgets.diamond-navigation-widget';
    
    // Customize the widget height
    protected int | string | array $columnSpan = 'full';
    
    public function render(): View
    {
        $subsystemsCount = is_array(Filament::getNavigationGroups()) ? count(Filament::getNavigationGroups()) - 3 : 8;
        return view('filament.widgets.diamond-navigation-widget',[
            'subsystemsCount' => $subsystemsCount
        ]);
    }
}
