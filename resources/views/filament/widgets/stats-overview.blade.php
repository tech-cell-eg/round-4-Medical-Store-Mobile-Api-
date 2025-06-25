<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col gap-6">

            @foreach ($stats as $stat)
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    @if (isset($stat['icon']))
                    <x-filament::icon
                        :icon="$stat['icon']"
                        @class([ 'fi-wi-stats-overview-stat-icon h-6 w-6' ,
                        match ($stat['color'] ?? null) { 'danger'=> 'text-danger-600 dark:text-danger-400',
                        'gray', null => 'text-gray-600 dark:text-gray-400',
                        'info' => 'text-info-600 dark:text-info-400',
                        'primary' => 'text-primary-600 dark:text-primary-400',
                        'success' => 'text-success-600 dark:text-success-400',
                        'warning' => 'text-warning-600 dark:text-warning-400',
                        default => $stat['color'],
                        },
                        ])
                        />
                        @endif

                        <div class="flex flex-col">

                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                ___+ {{ $stat['label'] }}
                            </span>

                            <span class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                                ___+ {{ $stat['value'] }}
                            </span>

                            @if (isset($stat['description']))
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                ___+ {{ $stat['description'] }}
                            </span>
                            @endif
                        </div>
                </div>
            </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>