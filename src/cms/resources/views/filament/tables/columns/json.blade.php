<div>
    <p class="text-sm text-gray-950 dark:text-white">
        @foreach ($context as $contextKey => $contextValue)
            <span class="text-gray-500 dark:text-gray-400">{{ $contextKey }}:&nbsp;</span>
            {{ $contextValue }}<br/>
        @endforeach
    </p>
</div>
