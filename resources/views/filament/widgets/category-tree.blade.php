{{-- resources/views/filament/widgets/category-tree.blade.php --}}
<x-filament::widget>
    <x-filament::card>
        <div class="flex items-center space-x-2 rtl:space-x-reverse mb-4">
            <x-heroicon-o-squares-2x2 class="w-5 h-5 text-primary-500" />
            <h3 class="text-lg font-semibold text-gray-900">{{ __('category.tree.title') }}</h3>
        </div>
        
        <div class="space-y-2">
            @if($categories->count() > 0)
                @foreach($categories as $category)
                    <x-filament.tree-item
                        :item="$category"
                        :level="0"
                    />
                @endforeach
            @else
                <div class="text-center py-8 text-gray-500">
                    <x-heroicon-o-folder class="w-12 h-12 mx-auto mb-4 text-gray-300" />
                    <p>{{ __('category.tree.no_categories') }}</p>
                </div>
            @endif
        </div>
    </x-filament::card>
</x-filament::widget>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add collapse functionality to tree items
        const treeItems = document.querySelectorAll('.tree-item');
        
        treeItems.forEach(item => {
            const toggle = item.querySelector('.tree-toggle');
            const children = item.querySelector('.tree-children');
            
            if (toggle && children) {
                toggle.addEventListener('click', function() {
                    const isExpanded = children.style.display !== 'none';
                    children.style.display = isExpanded ? 'none' : 'block';
                    
                    const icon = toggle.querySelector('svg');
                    if (icon) {
                        icon.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(90deg)';
                    }
                });
            }
        });
    });
</script>
@endpush