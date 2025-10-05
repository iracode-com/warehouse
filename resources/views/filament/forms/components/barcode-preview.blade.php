@php
    $barcode = $get('barcode');
    $record = $getRecord();
@endphp

@if($barcode && $record)
    <div class="flex flex-col items-center space-y-2 p-4 border rounded-lg bg-gray-50">
        <div class="text-sm font-medium text-gray-700">پیش‌نمایش بارکد:</div>
        <div class="bg-white p-2 rounded border">
            <img src="{{ $record->barcode_image }}" alt="بارکد" class="max-w-full h-auto" style="max-width: 200px; height: 60px;">
        </div>
        <div class="text-xs text-gray-500 font-mono">{{ $barcode }}</div>
    </div>
@endif
