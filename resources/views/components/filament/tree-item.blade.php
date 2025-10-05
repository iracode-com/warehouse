{{-- resources/views/components/filament/tree-item.blade.php --}}
@props(['item', 'level' => 0])

<div class="tree-item border border-gray-200 rounded-lg mb-2">
    <div class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100">
        <div class="flex items-center space-x-3 rtl:space-x-reverse">
            {{-- Indentation based on level --}}
            <div class="flex space-x-1 rtl:space-x-reverse" style="margin-left: {{ $level * 20 }}px;">
                @if($item->children && $item->children->count() > 0)
                    <button type="button" class="tree-toggle p-1 rounded hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 text-gray-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                @else
                    <div class="w-4 h-4"></div> {{-- Spacer for alignment --}}
                @endif
                
                {{-- Category icon and name --}}
                <x-heroicon-o-folder class="w-5 h-5 text-primary-500" />
                <span class="font-medium text-gray-900">{{ $item->name }}</span>
            </div>
        </div>
        
        {{-- Optional: Action buttons --}}
        <div class="flex items-center space-x-2 rtl:space-x-reverse">
            <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded">
                {{ $item->children_count ?? $item->children->count() }} {{ __('category.children') }}
            </span>
        </div>
    </div>
    
    {{-- Children --}}
    @if($item->children && $item->children->count() > 0)
        <div class="tree-children pl-6 border-t border-gray-200" style="display: none;">
            @foreach($item->children as $child)
                <x-filament.tree-item
                    :item="$child"
                    :level="$level + 1"
                />
            @endforeach
        </div>
    @endif
</div>