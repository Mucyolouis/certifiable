<x-filament::page>
    <x-filament::card>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Monthly Baptism Predictions</h2>
            <x-filament::button wire:click="refreshPredictions">
                Refresh
            </x-filament::button>
        </div>

        @if(count($predictions) > 0)
            <div class="grid gap-4 md:grid-cols-3">
                @foreach($predictions as $prediction)
                    <div class="p-4 bg-white border border-gray-200 rounded-lg">
                        <div class="text-lg font-semibold text-gray-900">{{ $prediction['month'] }}</div>
                        <div class="mt-2">We predict that 
                            <div class="text-2xl font-bold text-primary-600">
                                {{ $prediction['predicted_baptisms'] }} 
                            </div>Users will be Baptized.
                            <div class="text-sm text-gray-500">
                                Expected range: {{ $prediction['range']['min'] }} - {{ $prediction['range']['max'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-12 text-center">
                <div class="mb-2 text-gray-400">No predictions available</div>
                <div class="text-sm text-gray-500">
                    There isn't enough historical data to generate reliable predictions.
                </div>
            </div>
        @endif
    </x-filament::card>

    <x-filament::card class="mt-6">
        <h3 class="mb-4 text-lg font-medium">About Baptism Predictions</h3>
        <p class="text-gray-600">
            These predictions are based on historical baptism data and use machine learning to identify patterns and trends. 
            The predictions show expected baptisms for the next 6 months, with confidence ranges indicating potential variation.
        </p>
    </x-filament::card>
</x-filament::page>