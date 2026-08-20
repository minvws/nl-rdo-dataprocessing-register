@props([
    'actions' => [],
    'color' => 'gray',
    'icon' => null,
    'tooltip' => null,
])

<div
    {{
        $attributes->class([
            'fi-fo-field-wrp-hint flex items-center gap-x-3 text-sm',
        ])
    }}
>
    @if (! \Filament\Support\is_slot_empty($slot))
        <span
            @class([
                'fi-fo-field-wrp-hint-label',
                match ($color) {
                    'gray' => 'text-gray-500 dark:text-gray-400',
                    default => 'fi-color-custom text-custom-600 dark:text-custom-400',
                },
                is_string($color) ? "fi-color-{$color}" : null,
            ])
            @style([
                \Filament\Support\get_color_css_variables(
                    $color,
                    shades: [400, 600],
                    alias: 'forms::components.field-wrapper.hint.label',
                ),
            ])
        >
            {{ $slot }}
        </span>
    @endif

    @if ($icon)
        @php
            $iconClasses = \Illuminate\Support\Arr::toCssClasses([
                'fi-fo-field-wrp-hint-icon h-5 w-5',
                match ($color) {
                    'gray' => 'text-gray-400 dark:text-gray-500',
                    default => 'text-custom-500 dark:text-custom-400',
                },
            ]);
            $iconStyles = \Filament\Support\get_color_css_variables(
                $color,
                shades: [400, 500],
                alias: 'forms::components.field-wrapper.hint.icon',
            );
        @endphp

        @if (filled($tooltip))
            <span x-data="{}" x-id="['fi-fo-field-wrp-hint']" class="flex items-center">
                <button
                    type="button"
                    class="fi-fo-field-wrp-hint-icon-btn flex items-center rounded-full"
                    x-bind:aria-describedby="$id('fi-fo-field-wrp-hint')"
                    x-tooltip="{ content: {{ \Illuminate\Support\Js::from($tooltip) }}, theme: $store.theme }"
                >
                    <x-filament::icon
                        :icon="$icon"
                        :class="$iconClasses"
                        :style="$iconStyles"
                    />

                    <span class="sr-only">{{ __('general.hint') }}</span>
                </button>

                <span
                    class="sr-only"
                    x-bind:id="$id('fi-fo-field-wrp-hint')"
                >
                    {{ $tooltip }}
                </span>
            </span>
        @else
            <x-filament::icon
                :icon="$icon"
                :class="$iconClasses"
                :style="$iconStyles"
            />
        @endif
    @endif

    @if (count($actions))
        <div class="fi-fo-field-wrp-hint-action flex items-center gap-3">
            @foreach ($actions as $action)
                {{ $action }}
            @endforeach
        </div>
    @endif
</div>
