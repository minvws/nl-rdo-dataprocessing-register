<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\StaticWebsite\BuildEvent;
use App\Models\PublicWebsiteTree;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Log;
use Webmozart\Assert\Assert;

class PublicWebsiteTreeObserver
{
    public function created(PublicWebsiteTree $publicWebsiteTree): void
    {
        if ($publicWebsiteTree->public_from !== null && $publicWebsiteTree->public_from->isPast()) {
            $this->buildAction();
        }
    }

    public function updated(PublicWebsiteTree $publicWebsiteTree): void
    {
        $originalPublicFrom = $publicWebsiteTree->getOriginal('public_from');
        Assert::nullOrIsInstanceOf($originalPublicFrom, CarbonInterface::class);
        $originalPublicFromIsPast = $originalPublicFrom !== null && $originalPublicFrom->isPast();

        $currentPublicFrom = $publicWebsiteTree->public_from;
        $currentPublicFromIsPast = $currentPublicFrom !== null && $currentPublicFrom->isPast();

        if ($originalPublicFromIsPast || $currentPublicFromIsPast) {
            $this->buildAction();
        }
    }

    public function deleted(PublicWebsiteTree $publicWebsiteTree): void
    {
        if ($publicWebsiteTree->public_from !== null && $publicWebsiteTree->public_from->isPast()) {
            $this->buildAction();
        }
    }

    private function buildAction(): void
    {
        Log::debug('build event triggered by public website tree observer');

        BuildEvent::dispatch();
    }
}
