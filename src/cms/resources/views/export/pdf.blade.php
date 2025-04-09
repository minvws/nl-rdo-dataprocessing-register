<html>
<head>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
    </style>
</head>
<body>

<ht>
    Export {{ config('app.name') }} {{ DateFormat::toDateTime(now()) }}
</ht>

<div style="--cols-default: repeat(1, minmax(0, 1fr));"
     class="grid grid-cols-[--cols-default] fi-fo-component-ctn gap-6">
    <div style="--col-span-default: 1 / -1;" class="col-[--col-span-default]">
        <section x-data="{isCollapsed:  false}"
                 class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
                 id="gegevens" data-has-alpine-state="true">
            <header class="fi-section-header flex flex-col gap-3 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="grid flex-1 gap-y-1">
                        <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                            {{ __('snapshot.properties') }}
                        </h3>
                    </div>
                </div>
            </header>

            <div class="fi-section-content-ctn border-t border-gray-200 dark:border-white/10">
                <div class="fi-section-content p-6">
                    <dl>
                        <div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(2, minmax(0, 1fr));"
                             class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-fo-component-ctn gap-6">
                            <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                <div class="fi-in-entry-wrp">
                                    <div class="grid gap-y-2">
                                        <div class="flex items-center gap-x-3 justify-between ">
                                            <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                    {{ __('snapshot.snapshot_source_type') }}
                                                </span>
                                            </dt>
                                        </div>
                                        <div class="grid auto-cols-fr gap-y-2">
                                            <dd class="">
                                                <div class="fi-in-text w-full">
                                                    <div class="fi-in-affixes flex">
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex ">
                                                                <div class="flex max-w-max" style="">
                                                                    <div
                                                                        class="fi-in-text-item inline-flex items-center gap-1.5  ">
                                                                        <div
                                                                            class="text-sm leading-6 text-gray-950 dark:text-white  "
                                                                            style="">
                                                                            {{ __(sprintf('%s.model_singular', Str::snake(class_basename($record->snapshot_source_type)))) }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                <div class="fi-in-entry-wrp">
                                    <div class="grid gap-y-2">
                                        <div class="flex items-center gap-x-3 justify-between ">
                                            <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                    {{ __('snapshot.snapshot_source_display_name') }}
                                                </span>
                                            </dt>
                                        </div>
                                        <div class="grid auto-cols-fr gap-y-2">
                                            <dd class="">
                                                <div class="fi-in-text w-full">
                                                    <div class="fi-in-affixes flex">
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex ">
                                                                <div class="flex max-w-max" style="">
                                                                    <div
                                                                        class="fi-in-text-item inline-flex items-center gap-1.5  ">
                                                                        <div
                                                                            class="text-sm leading-6 text-gray-950 dark:text-white  "
                                                                            style="">
                                                                            {{ $record->snapshotSource->getDisplayName() }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                <div class="fi-in-entry-wrp">
                                    <div class="grid gap-y-2">
                                        <div class="flex items-center gap-x-3 justify-between ">
                                            <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                    {{ __('snapshot.version') }}
                                                </span>
                                            </dt>
                                        </div>
                                        <div class="grid auto-cols-fr gap-y-2">
                                            <dd class="">
                                                <div class="fi-in-text w-full">
                                                    <div class="fi-in-affixes flex">
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex ">
                                                                <div class="flex max-w-max" style="">
                                                                    <div
                                                                        class="fi-in-text-item inline-flex items-center gap-1.5  ">
                                                                        <div
                                                                            class="text-sm leading-6 text-gray-950 dark:text-white  "
                                                                            style="">
                                                                            {{ $record->version }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                <div class="fi-in-entry-wrp">
                                    <div class="grid gap-y-2">
                                        <div class="flex items-center gap-x-3 justify-between ">
                                            <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                    {{ __('snapshot.name') }}
                                                </span>
                                            </dt>
                                        </div>
                                        <div class="grid auto-cols-fr gap-y-2">
                                            <dd class="">
                                                <div class="fi-in-text w-full">
                                                    <div class="fi-in-affixes flex">
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex ">
                                                                <div class="flex max-w-max" style="">
                                                                    <div
                                                                        class="fi-in-text-item inline-flex items-center gap-1.5  ">
                                                                        <div
                                                                            class="text-sm leading-6 text-gray-950 dark:text-white  "
                                                                            style="">
                                                                            {{ $record->name }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                <div class="fi-in-entry-wrp">
                                    <div class="grid gap-y-2">
                                        <div class="flex items-center gap-x-3 justify-between ">
                                            <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">
                                                <span
                                                    class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                    {{ __('snapshot.state') }}
                                                </span>
                                            </dt>
                                        </div>
                                        <div class="grid auto-cols-fr gap-y-2">
                                            <dd class="">
                                                <div class="fi-in-text w-full">
                                                    <div class="fi-in-affixes flex">
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex gap-1.5 flex-wrap ">
                                                                <div class="flex w-max" style="">
                                                                    <span
                                                                        style="--c-50:var(--info-50);--c-400:var(--info-400);--c-600:var(--info-600);"
                                                                        class="fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-info">
                                                                        <span class="grid">
                                                                            <span class="truncate">
                                                                                {{ __(sprintf('snapshot_state.label.%s', $record->state)) }}
                                                                            </span>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
        </section>
    </div>

    <div style="--col-span-default: 1 / -1;" class="col-[--col-span-default]">
        <section x-data="{isCollapsed:  false}"
                 class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
                 id="publieke-gegevens" data-has-alpine-state="true">
            <header class="fi-section-header flex flex-col gap-3 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="grid flex-1 gap-y-1">
                        <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                            {{ __('snapshot.public_data') }}
                        </h3>
                    </div>
                </div>
            </header>

            <div class="fi-section-content-ctn border-t border-gray-200 dark:border-white/10">
                <div class="fi-section-content p-6">
                    <dl>
                        <div style="--cols-default: repeat(1, minmax(0, 1fr));"
                             class="grid grid-cols-[--cols-default] fi-fo-component-ctn gap-6">
                            <div style="--col-span-default: span 2 / span 2;" class="col-[--col-span-default]">
                                <div class="fi-in-entry-wrp">
                                    <div class="grid gap-y-2">
                                        <div class="grid auto-cols-fr gap-y-2">
                                            <dd class="">
                                                <div class="fi-in-text w-full">
                                                    <div class="fi-in-affixes flex">
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex ">
                                                                <div class="flex max-w-max" style="">
                                                                    <div
                                                                        class="fi-in-text-item inline-flex items-center gap-1.5  ">
                                                                        <div
                                                                            class="fi-in-text-item-prose prose max-w-none dark:prose-invert [&amp;>*:first-child]:mt-0 [&amp;>*:last-child]:mb-0 pt-2 prose-sm text-sm leading-6 text-gray-950 dark:text-white  "
                                                                            style="">
                                                                            {!! $publicMarkdown !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
        </section>
    </div>

    <div style="--col-span-default: 1 / -1;" class="col-[--col-span-default]">
        <section x-data="{isCollapsed:  false}"
                 class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
                 id="private-gegevens" data-has-alpine-state="true">
            <header class="fi-section-header flex flex-col gap-3 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="grid flex-1 gap-y-1">
                        <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                            {{ __('snapshot.private_data') }}
                        </h3>
                    </div>
                </div>
            </header>

            <div class="fi-section-content-ctn border-t border-gray-200 dark:border-white/10">
                <div class="fi-section-content p-6">
                    <dl>
                        <div style="--cols-default: repeat(1, minmax(0, 1fr));"
                             class="grid grid-cols-[--cols-default] fi-fo-component-ctn gap-6">
                            <div style="--col-span-default: span 2 / span 2;" class="col-[--col-span-default]">
                                <div class="fi-in-entry-wrp">
                                    <div class="grid gap-y-2">
                                        <div class="grid auto-cols-fr gap-y-2">
                                            <dd class="">
                                                <div class="fi-in-text w-full">
                                                    <div class="fi-in-affixes flex">
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex ">
                                                                <div class="flex max-w-max" style="">
                                                                    <div
                                                                        class="fi-in-text-item inline-flex items-center gap-1.5">
                                                                        <div
                                                                            class="fi-in-text-item-prose prose max-w-none dark:prose-invert [&amp;>*:first-child]:mt-0 [&amp;>*:last-child]:mb-0 pt-2 prose-sm text-sm leading-6 text-gray-950 dark:text-white  "
                                                                            style="">
                                                                            {!! $privateMarkdown !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
</html>
