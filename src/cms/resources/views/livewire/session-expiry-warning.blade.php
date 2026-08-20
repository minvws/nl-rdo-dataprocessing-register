<div
    x-data="{
        expiresAt: Date.now() + {{ $lifetimeInSeconds }} * 1000,
        secondsLeft: {{ $lifetimeInSeconds }},
        isOpen: false,
        isDismissed: false,
        isRedirecting: false,
        channel: null,

        init() {
            setInterval(() => this.tick(), 1000)

            Livewire.hook('commit', ({ succeed }) => succeed(() => this.reset()))

            // The session is shared between tabs, so a request from any tab extends it for all of them
            // Without this the other tabs keep counting down and warn about an expiry that will not happen
            if ('BroadcastChannel' in window) {
                this.channel = new BroadcastChannel('session-expiry-warning')

                this.channel.onmessage = (event) => {
                    if (event.data > this.expiresAt) {
                        this.expiresAt = event.data
                        this.isOpen = false
                        this.isDismissed = false
                    }
                }

                // Send page load as new expiry time, so other tabs can reset their countdowns too
                this.channel.postMessage(this.expiresAt)
            }
        },

        tick() {
            this.secondsLeft = Math.round((this.expiresAt - Date.now()) / 1000)

            // The session has expired, so reload into the login screen with an explanatory notice
            if (this.secondsLeft <= 0) {
                if (!this.isRedirecting) {
                    this.isRedirecting = true
                    window.location.assign(@js($expiredLoginUrl))
                }

                return
            }

            if (this.secondsLeft > {{ $warnSecondsBeforeExpiry }} || this.isDismissed) {
                return
            }

            this.isOpen = true
        },

        reset() {
            this.expiresAt = Date.now() + {{ $lifetimeInSeconds }} * 1000
            this.isOpen = false
            this.isDismissed = false
            this.channel?.postMessage(this.expiresAt)
        },

        get timeLeft() {
            const seconds = Math.max(0, this.secondsLeft)

            return Math.floor(seconds / 60) + ':' + String(seconds % 60).padStart(2, '0')
        },
    }"
    x-show="isOpen"
    style="display: none"
    class="fi-session-expiry-warning fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 dark:bg-gray-950/75"
>
    <div
        role="alertdialog"
        aria-modal="true"
        aria-labelledby="session-expiry-warning-heading"
        aria-describedby="session-expiry-warning-description"
        x-trap.noscroll="isOpen"
        class="w-full max-w-sm rounded-xl bg-white p-6 shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
    >
        <h2
            id="session-expiry-warning-heading"
            class="text-base font-semibold text-gray-950 dark:text-white"
        >
            {{ __('session.expiry_warning_heading') }}
        </h2>

        <p
            id="session-expiry-warning-description"
            class="mt-1 text-sm text-gray-700 dark:text-gray-300"
            x-text="@js(__('session.expiry_warning_description')).replace(':time', timeLeft)"
        ></p>

        <div class="mt-4 flex items-center gap-x-3">
            <x-filament::button wire:click="$refresh">
                {{ __('session.extend') }}
            </x-filament::button>

            <x-filament::button color="gray" x-on:click="isOpen = false; isDismissed = true">
                {{ __('session.dismiss') }}
            </x-filament::button>
        </div>
    </div>
</div>
