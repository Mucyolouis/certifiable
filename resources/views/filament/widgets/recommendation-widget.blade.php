<x-filament-widgets::widget>
    <x-filament::section>
        <h2 class="text-lg font-medium">Recommendation Letter</h2>
        @if($canDownload)
            <x-filament::button
                color="success"
                icon="heroicon-m-clipboard-document-check"
                class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700"
                icon-position="before">
                    <a href="{{ route('generate-pdf-rec', ['id' => auth()->id()]) }}">
                        Recommendation Letter
                    </a>
            </x-filament::button>
    @else
        <p class="mt-4 text-sm text-gray-600">
            You are not eligible to download the baptism certificate at this time.
        </p>
    @endif
    </x-filament::section>
</x-filament-widgets::widget>
