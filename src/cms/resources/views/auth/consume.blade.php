<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $lang ?? app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name') }}</title>
    @filamentStyles
    {{ filament()->getTheme()->getHtml() }}
    {{ filament()->getFontHtml() }}
    <style>
        :root {
            --font-family: '{!! filament()->getFontFamily() !!}';
            --sidebar-width: {{ filament()->getSidebarWidth() }};
            --collapsed-sidebar-width: {{ filament()->getCollapsedSidebarWidth() }};
            --default-theme-mode: {{ filament()->getDefaultThemeMode()->value }};
        }
    </style>

    @stack('styles')
    @if (! filament()->hasDarkMode())
        <script>
            localStorage.setItem('theme', 'light')
        </script>
    @elseif (filament()->hasDarkModeForced())
        <script>
            localStorage.setItem('theme', 'dark')
        </script>
    @else
        <script>
            const theme = localStorage.getItem('theme') ??
            @js(filament()->getDefaultThemeMode()->value)

            if (
                theme === 'dark' ||
                (theme === 'system' &&
                    window.matchMedia('(prefers-color-scheme: dark)')
                        .matches)
            ) {
                document.documentElement.classList.add('dark')
            }
        </script>
    @endif
</head>
<body
    class="fi-body fi-panel-admin min-h-screen bg-gray-50 font-normal text-gray-950 antialiased dark:bg-gray-950 dark:text-white">
<div class="fi-simple-layout flex min-h-screen flex-col items-center">
    <div class="fi-simple-main-ctn flex w-full flex-grow items-center justify-center">
        <main
            class="fi-simple-main my-16 w-full bg-white px-6 py-12 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 sm:rounded-xl sm:px-12 max-w-lg"
        >
            <div>
                <section class="grid auto-cols-fr gap-y-6">
                    <header class="fi-simple-header flex flex-col items-center">
                        <div
                            class="fi-logo flex text-xl font-bold leading-5 tracking-tight text-gray-950 dark:text-white mb-4">
                            Verwerkingsregister
                        </div>
                    </header>
                    <p class="fi-simple-header-subheading mt-2 text-center text-sm text-gray-500 dark:text-gray-400">
                        {{ __('auth.confirm_message') }}
                    </p>
                    <form method="post" action="{{ $postUrl }}">
                        @csrf
                        <div class="fi-form-actions">
                            <div class="fi-ac gap-3 grid grid-cols-[repeat(auto-fit,minmax(0,1fr))]">
                                <x-filament::button type="submit">
                                    {{ __('auth.confirm_login') }}
                                </x-filament::button>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </main>
    </div>
</div>

</body>
</html>
