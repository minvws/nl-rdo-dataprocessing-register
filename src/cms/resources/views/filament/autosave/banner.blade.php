@island('draft-autosave-banner')
@if ($restorableDraftSavedAt !== null)
    <section
        role="region"
        aria-label="{{ __('draft.banner_label') }}"
        class="mb-6 mt-4 rounded-xl bg-amber-50 p-4 ring-1 ring-amber-600/30 dark:bg-amber-400/10 dark:ring-amber-400/30"
    >
        <p class="text-sm text-amber-900 dark:text-amber-200">
            {{ __('draft.banner_text', ['saved_at' => $restorableDraftSavedAt]) }}
        </p>

        <div class="mt-3 flex items-center gap-x-3">
            <x-filament::button x-on:click="$wire.restoreDraft().then(() => window.location.reload())">
                {{ __('draft.restore') }}
            </x-filament::button>

            <x-filament::button color="gray" wire:click="ignoreDraft">
                {{ __('draft.ignore') }}
            </x-filament::button>
        </div>
    </section>
@endif
@endisland
