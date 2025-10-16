@once
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
@endonce

@if($getState())
    <div class="flex items-center justify-center">
        <div class="qr-code-item qr-code-item-{{ $getRecord()->id }}" data-qrcode="{{ $getState() }}"></div>
    </div>
    
    @once
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeQRCodes();
        });
        
        // Also run when Livewire updates the table
        document.addEventListener('livewire:navigated', function() {
            initializeQRCodes();
        });
        
        function initializeQRCodes() {
            document.querySelectorAll('.qr-code-item[data-qrcode]').forEach(function(element) {
                if (!element.hasAttribute('data-initialized') && !element.hasChildNodes()) {
                    try {
                        new QRCode(element, {
                            text: element.getAttribute('data-qrcode'),
                            width: 50,
                            height: 50,
                            colorDark: "#000000",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.H
                        });
                        element.setAttribute('data-initialized', 'true');
                    } catch (error) {
                        console.error('Error generating QR code:', error);
                        element.innerHTML = '<span class="text-xs text-red-500">خطا</span>';
                    }
                }
            });
        }
    </script>
    @endonce
@else
    <span class="text-xs text-gray-400">-</span>
@endif

