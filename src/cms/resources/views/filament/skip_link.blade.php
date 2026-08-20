{{--
    First focusable element on every page, so that keyboard users can jump straight to the content (WCAG 2.4.1).
    Visually hidden until it receives focus.
--}}
<a
    href="#main-content"
    class="fi-skip-link sr-only focus:not-sr-only focus:fixed focus:start-4 focus:top-4 focus:z-50 focus:block focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-gray-950 focus:shadow-lg focus:ring-2 focus:ring-primary-600 dark:focus:bg-gray-900 dark:focus:text-white"
>
    {{ __('general.skip_to_content') }}
</a>
