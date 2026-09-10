<x-filament-panels::page>
    {{ __('import.help') }}
    <form wire:submit="submit" class="fi-form grid gap-y-6">
        {{ $this->form }}
        <div>
            <x-filament::button type="submit" size="sm">
                Import
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
