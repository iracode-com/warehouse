@once
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
@endonce

@if($getState())
    <div class="flex items-center justify-center">
        <svg class="barcode-item barcode-item-{{ $getRecord()->id }}" data-barcode="{{ $getState() }}"></svg>
    </div>
    
    @once
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeBarcodes();
        });
        
        // Also run when Livewire updates the table
        document.addEventListener('livewire:navigated', function() {
            initializeBarcodes();
        });
        
        function initializeBarcodes() {
            document.querySelectorAll('.barcode-item[data-barcode]').forEach(function(element) {
                if (!element.hasAttribute('data-initialized')) {
                    try {
                        JsBarcode(element, element.getAttribute('data-barcode'), {
                            format: "CODE128",
                            width: 1,
                            height: 30,
                            displayValue: false,
                            margin: 5
                        });
                        element.setAttribute('data-initialized', 'true');
                    } catch (error) {
                        console.error('Error generating barcode:', error);
                        element.innerHTML = '<text x="50%" y="50%" text-anchor="middle" class="text-xs fill-red-500">خطا</text>';
                    }
                }
            });
        }
    </script>
    @endonce
@else
    <span class="text-xs text-gray-400">-</span>
@endif

