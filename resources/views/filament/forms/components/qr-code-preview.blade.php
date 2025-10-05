@php
    $qrCode = $get('qr_code');
    $record = $getRecord();
@endphp

@if($qrCode && $record)
    <div class="flex flex-col items-center space-y-2 p-4 border rounded-lg bg-gray-50">
        <div class="text-sm font-medium text-gray-700">پیش‌نمایش کد QR:</div>
        <div class="bg-white p-2 rounded border">
            <img src="{{ $record->qr_code_image }}" alt="کد QR" class="max-w-full h-auto" style="max-width: 150px; height: 150px;">
        </div>
        <div class="text-xs text-gray-500 font-mono break-all text-center">{{ $qrCode }}</div>
    </div>
@endif
