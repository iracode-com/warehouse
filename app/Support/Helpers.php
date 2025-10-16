<?php

namespace App\Support;

use App\Models\Base\Setting;
use Filament\Forms;
use Filament\Infolists;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

if (! function_exists('App\Support\translate')) {
    function translate($key): string
    {
        return __(
            str($key)
                ->replace(['-', '_'], ' ')
                ->lower()
                ->ucfirst()
                ->remove(['.', '_id'])
                ->squish()
                ->value()
        );
    }
}

if (! function_exists('error')) {
    function error(string $title = null, string $message = null): void
    {
        Notification::make()
            ->danger()
            ->title($title ?? __('Error'))
            ->body($message)
            ->send();
    }
}

if (! function_exists('saved')) {
    function saved(string $message = null): void
    {
        Notification::make()
            ->success()
            ->title($message ?? __('Saved Successfully'))
            ->send();
    }
}


if (! function_exists('IRR')) {
    function IRR($price): false|string
    {
        return Number::currency($price, in: 'IRR', locale: 'fa');
    }
}

if (! function_exists('IRT')) {
    function IRT($price = null, $in = true, $delimiter = ', '): bool|string
    {
        if (Str::contains($price, $delimiter)) {
            $prices      = str($price)->explode($delimiter)->toArray();
            $pricesArray = Arr::map($prices, fn($price) => IRT($price));
            return implode(', ', $pricesArray);
        }

        $price = str(Number::format($price ?? 0, locale: 'fa'));

        if ($in) {
            $price = $price->append(' ')->append(__('IRT'));
        }

        return $price;
    }
}


if (! function_exists('formComponentsConfiguration')) {
    function formComponentsConfiguration(): void
    {
        Forms\Components\Field::configureUsing(fn($component) => $component->inlineLabel());
        Infolists\Components\Entry::configureUsing(fn($component) => $component->inlineLabel());

        Forms\Components\Checkbox::configureUsing(fn($component) => $component->inlineLabel(false));
        Forms\Components\Radio::configureUsing(fn($component) => $component->inline()->options([0 => __('No'), 1 => __('Yes')]));
    }
}

if (! function_exists('loading')) {
    function loading($target): false|string
    {
        return Blade::render('<x-filament::loading-indicator wire:loading wire:target="' . $target . '" class="h-5 w-5"/>');
    }
}

if (! function_exists('setting')) {
    function setting($column)
    {
        if (! Schema::hasTable('settings')) {
            return null;
        }

        $setting = Setting::query()->where('name', $column)->first()?->payload;

        if ($setting && json_validate($setting)) {
            $setting = json_decode($setting, true);
        }

        return $setting ?? null;
    }
}
