<x-filament-widgets::widget>
    <x-filament::section>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-primary-100 dark:bg-primary-900 text-primary-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ms-4">
                        <h2 class="text-3xl font-semibold text-gray-800 dark:text-white">{{ $userCount }}</h2>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">المستخدمين</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-warning-100 dark:bg-warning-900 text-warning-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div class="ms-4">
                        <h2 class="text-3xl font-semibold text-gray-800 dark:text-white">{{ $productCount }}</h2>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">المنتجات</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-success-100 dark:bg-success-900 text-success-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="ms-4">
                        <h2 class="text-3xl font-semibold text-gray-800 dark:text-white">{{ $categoryCount }}</h2>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">الفئات</p>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-info-100 dark:bg-info-900 text-info-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ms-4">
                        <h2 class="text-3xl font-semibold text-gray-800 dark:text-white">{{ $brandCount }}</h2>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">العلامات التجارية</p>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
