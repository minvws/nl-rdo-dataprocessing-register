<div
    x-data="{}"
    x-on:keydown.escape.window="if (! window.matchMedia('(min-width: 1024px)').matches) { $store.sidebar.close() }"
></div>
