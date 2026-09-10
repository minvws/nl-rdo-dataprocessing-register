<x-filament-panels::page.simple>
    <form wire:submit="authenticate" class="fi-form grid gap-y-6">
        {{ $this->form }}

        <x-filament::actions :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()" />
    </form>
</x-filament-panels::page.simple>
