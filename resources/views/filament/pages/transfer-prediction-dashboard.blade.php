<x-filament-panels::page>
    <div class="p-4 bg-white rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Transfer Prediction</h2>
        <p class="text-lg">{{ $this->getPredictedTransferPercentage() }}</p>
    </div>
    <livewire:transfer-prediction-dashboard />
</x-filament-panels::page>
