@php
    $images = $getState();
@endphp

@if(!empty($images) && is_array($images) && count($images) > 0)
    <div class="space-y-4">
        <!-- تصویر اصلی -->
        @if(count($images) == 1)
            <div class="flex justify-center">
                <img 
                    src="{{ $images[0] }}" 
                    alt="تصویر کالا"
                    class="max-w-full h-auto max-h-96 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 cursor-pointer"
                    onclick="openImageModal('{{ $images[0] }}', 0)"
                >
            </div>
        @else
            <!-- گالری تصاویر -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($images as $index => $image)
                    <div class="relative group">
                        <img 
                            src="{{ $image }}" 
                            alt="تصویر کالا {{ $index + 1 }}"
                            class="w-full h-32 object-cover rounded-lg shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer"
                            onclick="openImageModal('{{ $image }}', {{ $index }})"
                        >
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                </svg>
                            </div>
                        </div>
                        @if($index == 0)
                            <div class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded">
                                تصویر اصلی
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- اطلاعات تعداد تصاویر -->
        <div class="text-center text-sm text-gray-600">
            <span class="inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ count($images) }} تصویر
            </span>
        </div>
    </div>

    <!-- Modal for image preview -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
        <div class="relative max-w-6xl max-h-full">
            <button 
                onclick="closeImageModal()" 
                class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors z-10"
            >
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full rounded-lg shadow-2xl">
        </div>
    </div>

    <script>
        function openImageModal(imageSrc, index) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            
            modalImage.src = imageSrc;
            modalImage.alt = `تصویر کالا ${index + 1}`;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@else
    <div class="text-center py-12 text-gray-500">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">تصویری ثبت نشده</h3>
        <p class="text-gray-500">برای این کالا هنوز تصویری آپلود نشده است</p>
    </div>
@endif
