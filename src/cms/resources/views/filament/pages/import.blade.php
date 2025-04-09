<x-filament-panels::page>
    {{ __('import.help') }}
    <x-filament-panels::form wire:submit="submit">
        {{ $this->form }}
        <div>
            <x-filament::button type="submit" size="sm">
                Import
            </x-filament::button>
        </div>
    </x-filament-panels::form>
</x-filament-panels::page>
