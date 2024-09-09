<x-filament-panels::page.simple>
    <x-slot name="heading">
        {{ __('filament-panels::pages/auth/register.title') }}
    </x-slot>

    <div class="w-full max-w-6xl mx-auto"> <!-- Increased max width -->
        <x-filament-panels::form wire:submit="register">
            {{ $this->form }}

            <x-filament-panels::form.actions
                :actions="$this->getCachedFormActions()"
                :full-width="$this->hasFullWidthFormActions()"
            />
        </x-filament-panels::form>
    </div>
</x-filament-panels::page.simple>