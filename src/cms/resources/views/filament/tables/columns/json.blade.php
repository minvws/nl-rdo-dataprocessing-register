<div>
    <p class="text-sm text-gray-950 dark:text-white">
        @if(is_array($getState()))
            @foreach ($getState() as $contextKey => $contextValue)
                <span class="text-gray-500 dark:text-gray-400">{{ $contextKey }}:&nbsp;</span>
                {{ $contextValue }}<br/>
            @endforeach
        @endif
    </p>
</div>
