{{-- resources/views/filament/resources/transfer-request-prediction/pages/view-transfer-predictions.blade.php --}}
<x-filament::page>
    <div class="space-y-6">
        {{-- Filters --}}
        <x-filament::card>
            <div class="flex flex-col items-center justify-between gap-4 p-4 md:flex-row">
                <div class="flex flex-col flex-grow gap-4 md:flex-row">
                    <select wire:model.live="selectedChurch" class="w-full text-gray-900 border-gray-300 rounded-lg shadow-sm md:w-64">
                        <option value="all">All Churches</option>
                        @foreach($churches as $church)
                            <option value="{{ $church->id }}">{{ $church->name }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="direction" class="w-full text-gray-900 border-gray-300 rounded-lg shadow-sm md:w-48">
                        @foreach($directions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="selectedMonth" class="w-full text-gray-900 border-gray-300 rounded-lg shadow-sm md:w-48">
                        @foreach($futureMonths as $month => $label)
                            <option value="{{ $month }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <x-filament::button wire:click="refreshPredictions">
                    Refresh Predictions
                </x-filament::button>
            </div>
        </x-filament::card>

        {{-- Predictions --}}
        @if(count($predictions) > 0)
            <div class="grid gap-6 md:grid-cols-2">
                {{-- Main Prediction Card --}}
                <x-filament::card>
                    <h3 class="mb-4 text-lg font-medium text-gray-900">
                        Transfer Predictions for {{ $predictions[0]['month'] }}
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Expected Transfers:</span>
                            <span class="text-2xl font-bold text-primary-600">
                                {{ $predictions[0]['predicted_transfers'] }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Confidence Range:</span>
                            <span class="text-gray-900">
                                {{ $predictions[0]['confidence_range']['min'] }} - {{ $predictions[0]['confidence_range']['max'] }}
                            </span>
                        </div>
                    </div>
                </x-filament::card>

                {{-- Approval Rates Card --}}
                <x-filament::card>
                    <h3 class="mb-4 text-lg font-medium text-gray-900">Predicted Approval Rates</h3>
                    <div class="space-y-3">
                        @foreach($predictions[0]['approval_rate'] as $status => $rate)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 capitalize">{{ $status }}:</span>
                                <div class="flex items-center">
                                    <div class="w-32 h-2 mr-2 bg-gray-200 rounded-full">
                                        <div class="h-2 rounded-full 
                                            @if($status === 'approved') bg-success-500
                                            @elseif($status === 'rejected') bg-danger-500
                                            @else bg-warning-500
                                            @endif"
                                            style="width: {{ $rate }}%">
                                        </div>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $rate }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-filament::card>
            </div>

            {{-- Additional Insights --}}
            <div class="grid gap-6 md:grid-cols-2">
                {{-- Popular Routes --}}
                <x-filament::card>
                    <h3 class="mb-4 text-lg font-medium text-gray-900">Popular Transfer Routes</h3>
                    <div class="space-y-3">
                        @foreach($predictions[0]['popular_routes'] as $route)
                            <div class="flex items-center justify-between p-2 rounded bg-gray-50">
                                <div class="flex items-center">
                                    <span class="text-gray-600">{{ $route['from'] }}</span>
                                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                    <span class="text-gray-600">{{ $route['to'] }}</span>
                                </div>
                                <span class="font-medium text-primary-600">{{ $route['count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-filament::card>

                {{-- Common Reasons --}}
                <x-filament::card>
                    <h3 class="mb-4 text-lg font-medium text-gray-900">Common Transfer Reasons</h3>
                    <div class="space-y-3">
                        @foreach($predictions[0]['likely_reasons'] as $reason => $count)
                            <div class="flex items-center justify-between p-2 rounded bg-gray-50">
                                <span class="text-gray-600 capitalize">{{ $reason }}</span>
                                <span class="font-medium text-primary-600">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-filament::card>
            </div>
        @else
            <x-filament::card>
                <div class="py-6 text-center">
                    <div class="mb-2 text-gray-400">No predictions available</div>
                    <div class="text-sm text-gray-500">
                        There isn't enough historical data to generate reliable predictions for the selected filters.
                    </div>
                </div>
            </x-filament::card>
        @endif

        {{-- Historical Statistics --}}
        @if(isset($statistics['totals']))
            <x-filament::card>
                <h3 class="mb-4 text-lg font-medium text-gray-900">Historical Statistics</h3>
                <div class="grid gap-4 md:grid-cols-4">
                    <div class="p-4 rounded bg-gray-50">
                        <div class="text-sm text-gray-600">Total Transfers</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $statistics['totals']['total'] }}</div>
                    </div>
                    <div class="p-4 rounded bg-gray-50">
                        <div class="text-sm text-gray-600">Average Approval Rate</div>
                        <div class="text-2xl font-bold text-success-600">{{ $statistics['approval_rate'] }}%</div>
                    </div>
                    <div class="p-4 rounded bg-gray-50">
                        <div class="text-sm text-gray-600">Pending Requests</div>
                        <div class="text-2xl font-bold text-warning-600">{{ $statistics['totals']['pending'] }}</div>
                    </div>
                    <div class="p-4 rounded bg-gray-50">
                        <div class="text-sm text-gray-600">Rejection Rate</div>
                        <div class="text-2xl font-bold text-danger-600">
                            {{ $statistics['totals']['total'] > 0 
                                ? round(($statistics['totals']['rejected'] / $statistics['totals']['total']) * 100, 1) 
                                : 0 }}%
                        </div>
                    </div>
                </div>
            </x-filament::card>
        @endif
    </div>
</x-filament::page>