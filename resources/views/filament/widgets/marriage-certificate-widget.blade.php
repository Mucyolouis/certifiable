<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-medium">Marriage Certificate</h2>
        {{-- <p class="mt-1 text-sm text-gray-600">Click to download</p> --}}
        <div class="mt-4">
            <x-filament::button wire:click="downloadCertificate">
                Download Certificate
            </x-filament::button>
        </div>
    </x-filament::card>
</x-filament::widget>