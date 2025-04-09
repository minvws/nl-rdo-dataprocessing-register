@php use Carbon\CarbonImmutable; @endphp
<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <ul class="list-inside list-disc">
        @foreach($getRecord()->media as $media)
            <li class="text-gray-950 dark:text-white">
                <div class="fi-in-text-item inline-flex items-center gap-1.5">
                    <div class="text-sm leading-6 text-gray-950 dark:text-white">
                        <a href="{{ $media->getFullUrl() }}">
                            {{ $media->name }} ({{ $media->humanReadableSize }})
                        </a>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</x-dynamic-component>
