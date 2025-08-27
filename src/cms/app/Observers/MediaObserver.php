<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\Media\MediaGroup;
use App\Events\PublicWebsite;
use App\Events\StaticWebsite;
use App\Models\Organisation;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Support\Facades\Log;
use Webmozart\Assert\Assert;

use function in_array;

class MediaObserver
{
    /** @var array<string> */
    private array $triggerGroups;

    public function __construct()
    {
        $this->triggerGroups = [
            MediaGroup::ORGANISATION_POSTERS->value,
        ];
    }

    public function created(Media $media): void
    {
        $this->dispatchIfInTriggerGroup($media);
    }

    public function updated(Media $media): void
    {
        $this->dispatchIfInTriggerGroup($media);
    }

    public function deleted(Media $media): void
    {
        $this->dispatchIfInTriggerGroup($media);
    }

    private function dispatchIfInTriggerGroup(Media $media): void
    {
        if (in_array($media->collection_name, $this->triggerGroups, true)) {
            $organisation = $media->organisation;
            Assert::isInstanceOf($organisation, Organisation::class);

            $this->dispatchBuildEvent();
        }
    }

    private function dispatchBuildEvent(): void
    {
        Log::debug('build event triggered by media observer');

        PublicWebsite\BuildEvent::dispatch();
        StaticWebsite\BuildEvent::dispatch();
    }
}
