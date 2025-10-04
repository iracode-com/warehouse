@php
    // QR Code will be generated using JavaScript
@endphp

<div class="space-y-4">
    <div class="text-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            {{ __('product-profile.fields.qr_code') }}
        </h3>
        
        @if($qr_code)
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 inline-block">
                <div class="text-center mb-2">
                    <span class="text-sm font-mono text-gray-600 dark:text-gray-400">{{ $sku }}</span>
                </div>
                
                <div class="flex justify-center">
                    <div id="qr-code-{{ $sku }}" class="qr-code-container" data-qr-code="{{ $qr_code }}"></div>
                </div>
                
                <div class="text-center mt-2">
                    <span class="text-xs font-mono text-gray-500 dark:text-gray-500 break-all">{{ $qr_code }}</span>
                </div>
            </div>
        @else
            <div class="text-gray-500 dark:text-gray-400 text-sm">
                {{ __('product-profile.messages.no_qr_code') }}
            </div>
        @endif
    </div>
    
    @if($qr_code)
        <div class="flex justify-center space-x-2 space-x-reverse">
            <button 
                onclick="printQRCode()" 
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800"
            >
                <x-heroicon-o-printer class="w-4 h-4 ml-1" />
                {{ __('product-profile.actions.print_qr_code') }}
            </button>
            
            <button 
                onclick="copyToClipboard('{{ $qr_code }}')" 
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
            >
                <x-heroicon-o-clipboard class="w-4 h-4 ml-1" />
                {{ __('product-profile.actions.copy_qr_code') }}
            </button>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
// Generate QR code when page loads
document.addEventListener('DOMContentLoaded', function() {
    const qrElement = document.getElementById('qr-code-{{ $sku }}');
    if (qrElement && '{{ $qr_code }}') {
        try {
            QRCode.toCanvas(qrElement, '{{ $qr_code }}', {
                width: 150,
                height: 150,
                margin: 2,
                color: {
                    dark: '#000000',
                    light: '#FFFFFF'
                }
            }, function (error) {
                if (error) {
                    console.error('Error generating QR code:', error);
                    qrElement.innerHTML = '<div class="text-red-500 text-sm">خطا در تولید کد QR</div>';
                }
            });
        } catch (error) {
            console.error('Error generating QR code:', error);
            qrElement.innerHTML = '<div class="text-red-500 text-sm">خطا در تولید کد QR</div>';
        }
    }
});

function printQRCode() {
    const printWindow = window.open('', '_blank');
    const qrElement = document.querySelector('[data-qr-code]');
    
    if (qrElement) {
        printWindow.document.write(`
            <html>
                <head>
                    <title>{{ __('product-profile.fields.qr_code') }} - {{ $sku }}</title>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            text-align: center; 
                            padding: 20px;
                        }
                        .qr-container {
                            margin: 20px 0;
                        }
                        .sku-text {
                            font-size: 16px;
                            font-weight: bold;
                            margin-bottom: 10px;
                        }
                        .qr-text {
                            font-size: 12px;
                            font-family: monospace;
                            margin-top: 10px;
                            word-break: break-all;
                        }
                    </style>
                    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
                </head>
                <body>
                    <div class="qr-container">
                        <div class="sku-text">{{ $sku }}</div>
                        <canvas id="print-qr-code"></canvas>
                        <div class="qr-text">{{ $qr_code }}</div>
                    </div>
                    <script>
                        QRCode.toCanvas(document.getElementById('print-qr-code'), '{{ $qr_code }}', {
                            width: 150,
                            height: 150,
                            margin: 2,
                            color: {
                                dark: '#000000',
                                light: '#FFFFFF'
                            }
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
        notification.textContent = '{{ __("product-profile.messages.qr_code_copied") }}';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    });
}
</script>
