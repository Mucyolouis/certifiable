<x-filament-widgets::widget>
    <x-filament::section>
        <x-filament::card>
        <div class="flex items-center h-12 space-x-4 rtl:space-x-reverse">
            <h2 class="text-lg font-bold tracking-tight sm:text-xl">Verse of the Moment</h2>
        </div>
        
        <div>
            <!-- Your custom content here -->
            <p class="font-semibold">{{ $reference }}</p>
            <p class="mt-1">{{ $text }}</p>
            <x-filament::button wire:click="refresh" class="mt-4">
                Get Another Verse
            </x-filament::button>
        </div>
    </x-filament::card>
    </x-filament::section>
</x-filament-widgets::widget>
