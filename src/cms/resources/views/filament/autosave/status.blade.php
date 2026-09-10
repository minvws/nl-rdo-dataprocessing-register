@island('draft-autosave')
<div
    x-data="{
        saving: false,
        retryAt: 0,
        confirmationRequested: false,
        knownState: null,
        justSaved: false,
        hideTimer: null,
        pollTimer: null,
        hideAfterMs: 5000,
        retryDelayMs: 60000,
        pollIntervalMs: @js(\App\Config\Config::integer('autosave.poll_interval_seconds') * 1000),

        init() {
            this.knownState = this.currentState()

            this.pollTimer = setInterval(() => this.tick(), this.pollIntervalMs)
        },

        destroy() {
            clearInterval(this.pollTimer)
            clearTimeout(this.hideTimer)
        },

        onFormInput(event) {
            if ($wire.$el.contains(event.target)) {
                this.requestOverwriteConfirmation()
            }
        },

        onFormFocusOut(event) {
            if ($wire.$el.contains(event.target)) {
                this.tick()
            }
        },

        currentState() {
            return JSON.stringify($wire.data ?? null)
        },

        requestOverwriteConfirmation() {
            if (!this.awaitsOverwriteConfirmation() || this.confirmationRequested) {
                return
            }

            if (this.currentState() === this.knownState) {
                return
            }

            this.confirmationRequested = true
            $wire.mountAction('confirmDraftOverwrite')
        },

        awaitsOverwriteConfirmation() {
            return $wire.restorableDraftKey !== null
        },

        async tick() {
            if (this.saving || Date.now() < this.retryAt) {
                return
            }

            const state = this.currentState()

            if (state === this.knownState) {
                return
            }

            if (this.awaitsOverwriteConfirmation()) {
                this.requestOverwriteConfirmation()

                return
            }

            this.saving = true

            try {
                if (await $wire.$island('draft-autosave').saveDraft() === null) {
                    this.retryLater()
                } else {
                    this.knownState = state
                    this.confirmSaved()
                }
            } catch (error) {
                this.retryLater()
            } finally {
                this.saving = false
            }
        },

        confirmSaved() {
            this.justSaved = true

            clearTimeout(this.hideTimer)
            this.hideTimer = setTimeout(() => { this.justSaved = false }, this.hideAfterMs)
        },

        retryLater() {
            this.retryAt = Date.now() + this.retryDelayMs
        },
    }"
    x-on:input.window.capture="onFormInput($event)"
    x-on:change.window.capture="onFormInput($event)"
    x-on:focusout.window.capture="onFormFocusOut($event)"
    x-on:draft-overwrite-confirmed.window="tick()"
    class="fi-draft-autosave w-full sm:absolute sm:end-0 sm:top-0 sm:w-auto"
>
    <div aria-hidden="true" class="flex items-center justify-end text-sm text-gray-500 dark:text-gray-400">
        <span x-cloak x-show="saving" class="flex items-center gap-x-1.5">
            <x-filament::loading-indicator class="h-4 w-4" />
            {{ __('draft.saving') }}
        </span>

        <span x-cloak x-show="justSaved && !saving" class="fi-draft-autosave-saved flex items-center gap-x-1.5">
            <x-filament::icon icon="heroicon-m-check-circle" class="h-4 w-4 text-green-600 dark:text-green-400" />
            {{ __('draft.saved') }}
        </span>
    </div>

    <p role="status" aria-live="polite" class="sr-only">
        @if ($draftLastSavedAt !== null)
            {{ __('draft.saved_at', ['time' => $draftLastSavedAt]) }}
        @endif
    </p>
</div>
@endisland
