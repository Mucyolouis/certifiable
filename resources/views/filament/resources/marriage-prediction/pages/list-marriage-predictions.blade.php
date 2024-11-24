{{-- resources/views/marriage-prediction/pages/list-marriage-predictions.blade.php --}}
<x-filament::page>
    <x-filament::card>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Marriage Predictions</h2>
            <div class="flex items-center space-x-4">
                <select wire:model.live="predictionMonths" class="w-40 text-gray-900 border-gray-300 rounded-lg shadow-sm">
                    <option value="3">Next 3 months</option>
                    <option value="6">Next 6 months</option>
                    <option value="12">Next 12 months</option>
                </select>
                <x-filament::button wire:click="refreshPredictions">
                    Refresh
                </x-filament::button>
            </div>
        </div>

        @if(count($predictions) > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Month</th>
                            <th class="px-4 py-2 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Predicted Marriages</th>
                            <th class="px-4 py-2 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Confidence Range</th>
                            <th class="px-4 py-2 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Trend</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($predictions as $index => $prediction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 whitespace-nowrap">{{ $prediction['date'] }}</td>
                                <td class="px-4 py-2 font-medium text-right whitespace-nowrap">
                                    {{ $prediction['predicted_marriages'] }}
                                </td>
                                <td class="px-4 py-2 text-right text-gray-600 whitespace-nowrap">
                                    {{ $prediction['lower_bound'] }} - {{ $prediction['upper_bound'] }}
                                </td>
                                <td class="px-4 py-2 text-right whitespace-nowrap">
                                    @if($index > 0 && isset($predictions[$index - 1]))
                                        @php
                                            $prevValue = $predictions[$index - 1]['predicted_marriages'];
                                            $currentValue = $prediction['predicted_marriages'];
                                            $difference = $currentValue - $prevValue;
                                            $percentChange = $prevValue != 0 ? ($difference / $prevValue) * 100 : 0;
                                        @endphp
                                        
                                        @if($difference > 0)
                                            <span class="text-success-600">
                                                ↑ {{ number_format(abs($percentChange), 1) }}%
                                            </span>
                                        @elseif($difference < 0)
                                            <span class="text-danger-600">
                                                ↓ {{ number_format(abs($percentChange), 1) }}%
                                            </span>
                                        @else
                                            <span class="text-gray-600">
                                                − 0%
                                            </span>
                                        @endif
                                    @else
                                        −
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(!empty($metrics) && !$metrics['error'])
                <div class="grid grid-cols-1 gap-4 mt-6 md:grid-cols-3">
                    <x-filament::card>
                        <div class="text-sm text-gray-600">Accuracy</div>
                        <div class="text-lg font-semibold">{{ number_format($metrics['accuracy_percentage'], 1) }}%</div>
                        <div class="mt-1 text-xs text-gray-500">Based on historical data</div>
                    </x-filament::card>

                    <x-filament::card>
                        <div class="text-sm text-gray-600">Mean Absolute Error</div>
                        <div class="text-lg font-semibold">{{ number_format($metrics['mae'], 1) }}</div>
                        <div class="mt-1 text-xs text-gray-500">Average prediction error</div>
                    </x-filament::card>

                    <x-filament::card>
                        <div class="text-sm text-gray-600">Mean Squared Error</div>
                        <div class="text-lg font-semibold">{{ number_format($metrics['mse'], 1) }}</div>
                        <div class="mt-1 text-xs text-gray-500">Error variance indicator</div>
                    </x-filament::card>
                </div>
            @endif
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
        <h3 class="mb-4 text-lg font-medium">About Marriage Predictions</h3>
        <p class="text-gray-600">
            These predictions are based on historical marriage data and use machine learning to identify patterns and trends. 
            The confidence range indicates the expected variation in the predictions. Regular updates to the model help improve accuracy over time.
        </p>
    </x-filament::card>
</x-filament::page>