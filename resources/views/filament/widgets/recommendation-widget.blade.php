<x-filament-widgets::widget>
    <x-filament::section>
        <h2 class="text-lg font-medium">Recommendation Letter</h2>
        @if($canDownload)
            <x-filament::button
                color="success"
                icon="heroicon-m-clipboard-document-check"
                icon-position="before">
                    <a href="{{ route('generate-pdf-rec', ['id' => auth()->id()]) }}">
                        Recommendation Letter
                    </a>
            </x-filament::button>
        <div class="mt-4">
            
        </div>
    @else
        <p class="mt-4 text-sm text-gray-600">
            You are not eligible to download the baptism certificate at this time.
        </p>
    @endif
    </x-filament::section>
</x-filament-widgets::widget>
