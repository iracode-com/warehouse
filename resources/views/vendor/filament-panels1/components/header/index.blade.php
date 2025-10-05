@props([
    'actions' => [],
    'breadcrumbs' => [],
    'heading',
    'subheading' => null,
])

<header
    {{
        $attributes->class([
            'fi-header',
            'fi-header-has-breadcrumbs' => $breadcrumbs,
        ])
    }}
>
    <div>
        @if ($breadcrumbs)
            <x-filament::breadcrumbs :breadcrumbs="$breadcrumbs" />
        @endif


        <h1
            class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl"
        >
            {{ \Request::route()->getName() == 'filament.admin.pages.dashboard' ? __("Dashboard Title") : $heading }}
            @if(\Request::route()->getName() == 'filament.admin.pages.dashboard')
                <sub>{{ __("Dashboard Title Slogan") }}</sub>
            @endif
        </h1>
        @if(\Request::route()->getName() == 'filament.admin.pages.dashboard')
            <p class="fi-header-subheading text-sm" style="margin: 10px 0;">{{ __("Dashboard Slogan") }}</p>
        @endif

        {{-- <h1 class="fi-header-heading">
            {{ $heading }}
        </h1>

        @if ($subheading)
            <p class="fi-header-subheading">
                {{ $subheading }}
            </p>
        @endif --}}
    </div>

    @php
        $beforeActions = \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::PAGE_HEADER_ACTIONS_BEFORE, scopes: $this->getRenderHookScopes());
        $afterActions = \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::PAGE_HEADER_ACTIONS_AFTER, scopes: $this->getRenderHookScopes());
    @endphp

    @if (filled($beforeActions) || $actions || filled($afterActions))
        <div class="fi-header-actions-ctn">
            {{ $beforeActions }}

            @if ($actions)
                <x-filament::actions :actions="$actions" />
            @endif

            {{ $afterActions }}
        </div>
    @endif
</header>
