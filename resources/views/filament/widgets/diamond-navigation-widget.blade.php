<x-filament::widget class="fi-dashboard-widget">
    <x-filament::card class="overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-700 dark:to-primary-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">سامانه‌های مدیریت انبار</h3>
                    <p class="text-primary-100 text-sm mt-1">دسترسی سریع به ماژول‌های سیستم انبارداری</p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2">
                    <x-heroicon-o-squares-2x2 class="w-6 h-6 text-white" />
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="p-6 bg-gray-50 dark:bg-gray-900">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 gap-4">
                
                <!-- Warehouse Management -->
                <a href="{{ route('filament.admin.resources.warehouses.index') }}" 
                   class="group relative bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 
                          rounded-xl p-6 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 
                          transition-all duration-300 ease-out col-span-1 md:col-span-2 lg:col-span-1 min-h-[140px]
                          border border-blue-400/20 hover:border-blue-300/40">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <x-heroicon-o-building-office-2 class="w-8 h-8 text-blue-100 group-hover:text-white transition-colors duration-300" />
                            <span class="bg-white/20 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-medium">انبار</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">انبارها</h4>
                            <p class="text-blue-100 text-sm leading-relaxed">مدیریت اطلاعات انبارها</p>
                        </div>
                    </div>
                </a>

                <!-- Categories -->
                <a href="{{ route('filament.admin.resources.categories.index') }}" 
                   class="group relative bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 
                          rounded-xl p-6 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 
                          transition-all duration-300 ease-out min-h-[140px]
                          border border-green-400/20 hover:border-green-300/40">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <x-heroicon-o-tag class="w-8 h-8 text-green-100 group-hover:text-white transition-colors duration-300" />
                            <span class="bg-white/20 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-medium">دسته‌بندی</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">دسته‌بندی‌ها</h4>
                            <p class="text-green-100 text-sm leading-relaxed">مدیریت دسته‌بندی محصولات</p>
                        </div>
                    </div>
                </a>

                <!-- Product Profiles -->
                <a href="{{ route('filament.admin.resources.product-profiles.index') }}" 
                   class="group relative bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 
                          rounded-xl p-6 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 
                          transition-all duration-300 ease-out min-h-[140px]
                          border border-purple-400/20 hover:border-purple-300/40">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <x-heroicon-o-document-text class="w-8 h-8 text-purple-100 group-hover:text-white transition-colors duration-300" />
                            <span class="bg-white/20 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-medium">شناسنامه</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">شناسنامه کالا</h4>
                            <p class="text-purple-100 text-sm leading-relaxed">مدیریت شناسنامه‌های محصولات</p>
                        </div>
                    </div>
                </a>

                <!-- Items Management -->
                <a href="{{ route('filament.admin.resources.items.index') }}" 
                   class="group relative bg-gradient-to-br from-amber-500 to-amber-600 dark:from-amber-600 dark:to-amber-700 
                          rounded-xl p-6 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 
                          transition-all duration-300 ease-out min-h-[140px]
                          border border-amber-400/20 hover:border-amber-300/40">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <x-heroicon-o-cube class="w-8 h-8 text-amber-100 group-hover:text-white transition-colors duration-300" />
                            <span class="bg-white/20 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-medium">کالاها</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">کالاها</h4>
                            <p class="text-amber-100 text-sm leading-relaxed">مدیریت موجودی کالاها</p>
                        </div>
                    </div>
                </a>

                <!-- Personnel Management -->
                <a href="{{ route('filament.admin.resources.personnels.index') }}" 
                   class="group relative bg-gradient-to-br from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 
                          rounded-xl p-6 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 
                          transition-all duration-300 ease-out min-h-[140px]
                          border border-indigo-400/20 hover:border-indigo-300/40">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <x-heroicon-o-users class="w-8 h-8 text-indigo-100 group-hover:text-white transition-colors duration-300" />
                            <span class="bg-white/20 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-medium">پرسنل</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">پرسنل</h4>
                            <p class="text-indigo-100 text-sm leading-relaxed">مدیریت اطلاعات پرسنل</p>
                        </div>
                    </div>
                </a>

                <!-- Users Management -->
                <a href="{{ route('filament.admin.resources.users.index') }}" 
                   class="group relative bg-gradient-to-br from-emerald-600 to-emerald-700 dark:from-emerald-700 dark:to-emerald-800 
                          rounded-xl p-6 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 
                          transition-all duration-300 ease-out col-span-1 md:col-span-2 lg:col-span-1 min-h-[140px]
                          border border-emerald-400/20 hover:border-emerald-300/40">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <x-heroicon-o-user-group class="w-8 h-8 text-emerald-100 group-hover:text-white transition-colors duration-300" />
                            <span class="bg-white/20 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-medium">کاربران</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">کاربران</h4>
                            <p class="text-emerald-100 text-sm leading-relaxed">مدیریت کاربران سیستم</p>
                        </div>
                    </div>
                </a>

                <!-- Roles Management -->
                <a href="{{ route('filament.admin.resources.shield.roles.index') }}" 
                   class="group relative bg-gradient-to-br from-rose-600 to-rose-700 dark:from-rose-700 dark:to-rose-800 
                          rounded-xl p-6 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 
                          transition-all duration-300 ease-out col-span-1 md:col-span-2 lg:col-span-1 min-h-[140px]
                          border border-rose-400/20 hover:border-rose-300/40">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <x-heroicon-o-shield-check class="w-8 h-8 text-rose-100 group-hover:text-white transition-colors duration-300" />
                            <span class="bg-white/20 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-medium">نقش‌ها</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">نقش‌ها</h4>
                            <p class="text-rose-100 text-sm leading-relaxed">مدیریت نقش‌ها و دسترسی‌ها</p>
                        </div>
                    </div>
                </a>

                <!-- Regions Management -->
                <a href="{{ route('filament.admin.resources.regions.index') }}" 
                   class="group relative bg-gradient-to-br from-cyan-600 to-cyan-700 dark:from-cyan-700 dark:to-cyan-800 
                          rounded-xl p-6 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1 
                          transition-all duration-300 ease-out col-span-1 md:col-span-2 lg:col-span-1 min-h-[140px]
                          border border-cyan-400/20 hover:border-cyan-300/40">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div class="flex items-center justify-between mb-3">
                            <x-heroicon-o-map class="w-8 h-8 text-cyan-100 group-hover:text-white transition-colors duration-300" />
                            <span class="bg-white/20 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-medium">تقسیمات</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">تقسیمات کشوری</h4>
                            <p class="text-cyan-100 text-sm leading-relaxed">مدیریت استان‌ها و شهرها</p>
                        </div>
                    </div>
                </a>

            </div>
        </div>

        <!-- Footer Stats -->
        <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="group">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-300">{{ $subsystemsCount }}+</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">ماژول فعال</div>
                </div>
                <div class="group">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400 group-hover:text-green-500 transition-colors duration-300">99%</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">دقت سیستم</div>
                </div>
                <div class="group">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 group-hover:text-blue-500 transition-colors duration-300">24/7</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">دسترسی</div>
                </div>
                <div class="group">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 group-hover:text-purple-500 transition-colors duration-300">{{ \App\Models\User::count() }}+</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">کاربر فعال</div>
                </div>
            </div>
        </div>
    </x-filament::card>

    <style>
        /* RTL Support */
        .fi-dashboard-widget {
            direction: rtl;
        }
        
        /* Custom hover animations */
        .fi-dashboard-widget a:hover {
            transform: translateY(-4px);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .fi-dashboard-widget .grid {
                gap: 3rem;
            }
            
            .fi-dashboard-widget h4 {
                font-size: 1rem;
            }
            
            .fi-dashboard-widget p {
                font-size: 0.75rem;
            }
        }
        
        /* Theme-aware adjustments */
        @media (prefers-color-scheme: dark) {
            .fi-dashboard-widget .bg-gray-50 {
                background-color: rgb(17 24 39);
            }
        }
        
        /* Accessibility improvements */
        .fi-dashboard-widget a:focus {
            outline: 2px solid rgb(59 130 246);
            outline-offset: 2px;
        }
        
        /* Smooth transitions for all interactive elements */
        .fi-dashboard-widget * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
    </style>
</x-filament::widget>
