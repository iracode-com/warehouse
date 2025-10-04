@php
    // QR Code and Barcode will be generated using JavaScript
@endphp

<div class="space-y-4">
    <div class="text-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            {{ __('product-profile.fields.barcode') }}
        </h3>
        
        @if($barcode)
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 inline-block">
                <div class="text-center mb-2">
                    <span class="text-sm font-mono text-gray-600 dark:text-gray-400">{{ $sku }}</span>
                </div>
                
                <div class="flex justify-center">
                    <div id="barcode-{{ $sku }}" class="barcode-container" data-barcode="{{ $barcode }}"></div>
                </div>
                
                <div class="text-center mt-2">
                    <span class="text-xs font-mono text-gray-500 dark:text-gray-500">{{ $barcode }}</span>
                </div>
            </div>
        @else
            <div class="text-gray-500 dark:text-gray-400 text-sm">
                {{ __('product-profile.messages.no_barcode') }}
            </div>
        @endif
    </div>
    
    @if($barcode)
        <div class="flex justify-center space-x-2 space-x-reverse">
            <button 
                onclick="printBarcode()" 
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800"
            >
                <x-heroicon-o-printer class="w-4 h-4 ml-1" />
                {{ __('product-profile.actions.print_barcode') }}
            </button>
            
            <button 
                onclick="copyToClipboard('{{ $barcode }}')" 
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
            >
                <x-heroicon-o-clipboard class="w-4 h-4 ml-1" />
                {{ __('product-profile.actions.copy_barcode') }}
            </button>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
// Generate barcode when page loads
document.addEventListener('DOMContentLoaded', function() {
    const barcodeElement = document.getElementById('barcode-{{ $sku }}');
    if (barcodeElement && '{{ $barcode }}') {
        try {
            JsBarcode(barcodeElement, '{{ $barcode }}', {
                format: "CODE128",
                width: 2,
                height: 50,
                displayValue: false,
                margin: 10
            });
        } catch (error) {
            console.error('Error generating barcode:', error);
            barcodeElement.innerHTML = '<div class="text-red-500 text-sm">خطا در تولید بارکد</div>';
        }
    }
});

function printBarcode() {
    const printWindow = window.open('', '_blank');
    const barcodeElement = document.querySelector('[data-barcode]');
    
    if (barcodeElement) {
        printWindow.document.write(`
            <html>
                <head>
                    <title>{{ __('product-profile.fields.barcode') }} - {{ $sku }}</title>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            text-align: center; 
                            padding: 20px;
                        }
                        .barcode-container {
                            margin: 20px 0;
                        }
                        .sku-text {
                            font-size: 16px;
                            font-weight: bold;
                            margin-bottom: 10px;
                        }
                        .barcode-text {
                            font-size: 12px;
                            font-family: monospace;
                            margin-top: 10px;
                        }
                    </style>
                    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
                </head>
                <body>
                    <div class="barcode-container">
                        <div class="sku-text">{{ $sku }}</div>
                        <div id="print-barcode"></div>
                        <div class="barcode-text">{{ $barcode }}</div>
                    </div>
                    <script>
                        JsBarcode('#print-barcode', '{{ $barcode }}', {
                            format: "CODE128",
                            width: 2,
                            height: 50,
                            displayValue: false,
                            margin: 10
                        });
                    </script>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notification.textContent = '{{ __("product-profile.messages.barcode_copied") }}';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    });
}
</script>
