@php use App\Support\Utils; @endphp
@php(
    $tabs = [
        'filtering',
        'settings',
        'preview'
    ]
)

<x-filament-panels::page>

    <div x-data="{ 
        activeTab: $wire.entangle('activeTab').live,
        init() {
            // Get tab from URL on page load
            const urlParams = new URLSearchParams(window.location.search);
            const tabFromUrl = urlParams.get('tab');
            if (tabFromUrl && ['filtering', 'settings', 'preview'].includes(tabFromUrl)) {
                this.activeTab = tabFromUrl;
            }
            
            // Watch for tab changes and update URL
            this.$watch('activeTab', (newTab) => {
                const url = new URL(window.location);
                url.searchParams.set('tab', newTab);
                window.history.replaceState({}, '', url);
            });
        }
    }">
        
        <!-- Header with Progress -->
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-lg font-medium text-gray-600! dark:text-gray-400">
                        {{ __('Report Builder') }}
                    </h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ __('Create and customize your reports with ease') }}
                    </p>
                </div>
                <div class="flex items-center space-x-1 text-xs text-gray-500 dark:text-gray-400">
                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                    <span>{{ __('Active') }}</span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $record->step }}/3 {{ __('Steps Completed') }}</span>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 ml-4">
                    <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-300" 
                         style="width: {{ ($record->step / 3) * 100 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="mb-6 flex justify-center">
            <x-filament::tabs>
                <x-filament::tabs.item
                    alpine-active="activeTab === 'filtering'"
                    x-on:click="activeTab = 'filtering'"
                    icon="heroicon-o-funnel"
                    :badge="$record->step > 0 ? __('Done.') : ''"
                    badge-color="success"
                    badge-icon="heroicon-o-check-circle"
                    icon-color="success"
                    class="text-sm"
                >
                    {{ __('Filtering') }}
                </x-filament::tabs.item>

                @if($record->step > 0)
                    <x-filament::tabs.item
                        alpine-active="activeTab === 'settings'"
                        x-on:click="activeTab = 'settings'"
                        icon="heroicon-o-pencil-square"
                        :badge="$record->step > 1 ? __('Done.') : ''"
                        badge-color="success"
                        icon-color="success"
                        badge-icon="heroicon-o-check-circle"
                        class="text-sm">
                        {{ __('Settings') }}
                    </x-filament::tabs.item>
                @else
                    <x-filament::tabs.item 
                        icon="heroicon-o-pencil-square"
                        class="text-sm opacity-50 cursor-not-allowed">
                        {{ __('Settings') }}
                    </x-filament::tabs.item>
                @endif

                @if($record->step > 1)
                    <x-filament::tabs.item
                        alpine-active="activeTab === 'preview'"
                        x-on:click="activeTab = 'preview'"
                        icon="heroicon-o-clipboard-document"
                        :badge="$record->step > 2 ? __('Done.') : ''"
                        badge-color="success"
                        badge-icon="heroicon-o-check"
                        class="text-sm">
                        {{ __('Preview') }}
                    </x-filament::tabs.item>
                @else
                    <x-filament::tabs.item 
                        icon="heroicon-o-clipboard-document"
                        class="text-sm opacity-50 cursor-not-allowed">
                        {{ __('Preview') }}
                    </x-filament::tabs.item>
                @endif
            </x-filament::tabs>
        </div>

        <!-- Tab Content -->
        <div x-show="activeTab === 'filtering'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            {{ $this->table }}
        </div>

        <div x-show="activeTab === 'settings'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            <form wire:submit="submitSettings">
                {{ $this->form }}
                <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700 mt-6">
                    <x-filament::button 
                        type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded">
                        {{ __('Create Report') }}
                    </x-filament::button>
                </div>
            </form>
        </div>

        <div x-show="activeTab === 'preview'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            {{ $this->table }}
        </div>
    </div>

    <x-filament-actions::modals/>

    <style>
        /* Hide first two items (duplicate/copy buttons) in QueryBuilder */
        .fi-fo-builder-item-header-end-actions li:nth-child(1),
        .fi-fo-builder-item-header-end-actions li:nth-child(2) {
            display: none !important;
        }
    </style>

    <script>
        // Hide first two items in query builder actions
        function hideQueryBuilderButtons() {
            document.querySelectorAll('.fi-fo-builder-item-header-end-actions').forEach(ul => {
                const firstLi = ul.querySelector('li:nth-child(1)');
                const secondLi = ul.querySelector('li:nth-child(2)');
                if (firstLi) firstLi.style.display = 'none';
                if (secondLi) secondLi.style.display = 'none';
            });
        }
        
        // Run immediately (for already loaded content)
        hideQueryBuilderButtons();
        
        // Run on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', hideQueryBuilderButtons);
        } else {
            hideQueryBuilderButtons();
        }
        
        // Run after short delays to catch delayed renders
        setTimeout(hideQueryBuilderButtons, 100);
        setTimeout(hideQueryBuilderButtons, 500);
        setTimeout(hideQueryBuilderButtons, 1000);
        
        // Run after Livewire updates
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                succeed(() => {
                    setTimeout(hideQueryBuilderButtons, 50);
                    setTimeout(hideQueryBuilderButtons, 200);
                });
            });
        }
        
        // Use MutationObserver for dynamically added elements
        const observer = new MutationObserver(function(mutations) {
            hideQueryBuilderButtons();
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    </script>

</x-filament-panels::page>