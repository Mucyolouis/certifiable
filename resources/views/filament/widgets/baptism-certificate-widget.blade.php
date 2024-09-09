<x-filament-widgets::widget>
    <x-filament::card>
        <h2 class="text-lg font-medium">Baptism Certificate</h2>
        @if($canDownload)
            <div class="mt-4">
                <a href="{{ route('generate-pdf', ['id' => auth()->id()]) }}" 
                   class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700"
                   target="_blank">
                    Download Baptism Certificate
                </a>
            </div>
        @else
            <p class="mt-4 text-sm text-gray-600">
                You are not eligible to download the baptism certificate at this time.
            </p>
        @endif
    </x-filament::card>
</x-filament-widgets::widget>