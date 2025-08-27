<span @class(['hidden' => !$getRecord()->isPublished()])>
    <a href="{{ $getRecord()->getLatestPublicWebsiteSnapshotEntry()?->url }}"
       target="_blank"
       title="{{ __('general.published_at') }}"
    >
        <x-filament::icon
            icon="heroicon-o-globe-alt"
            class="h-8 w-8 text-gray-500"
            alias="link"
        />
    </a>
</span>
