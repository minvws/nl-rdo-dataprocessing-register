<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\PublicWebsite;
use App\Events\StaticWebsite;
use App\Models\Organisation;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Log;
use Webmozart\Assert\Assert;

class OrganisationObserver
{
    public function updated(Organisation $organisation): void
    {
        $originalPublicFrom = $organisation->getOriginal('public_from');
        Assert::nullOrIsInstanceOf($originalPublicFrom, CarbonInterface::class);
        $originalPublicFromIsPast = $originalPublicFrom !== null && $originalPublicFrom->isPast();

        $currentPublicFrom = $organisation->public_from;
        $currentPublicFromIsPast = $currentPublicFrom !== null && $currentPublicFrom->isPast();

        if ($originalPublicFromIsPast || $currentPublicFromIsPast) {
            $this->dispatchBuildEvent();
        }
    }

    public function deleted(Organisation $organisation): void
    {
        $originalPublicFrom = $organisation->getOriginal('public_from');
        Assert::nullOrIsInstanceOf($originalPublicFrom, CarbonInterface::class);
        $currentPublicFrom = $organisation->public_from;

        if ($currentPublicFrom !== null && $currentPublicFrom->isPast()) {
            $this->dispatchBuildEvent();
        }
    }

    private function dispatchBuildEvent(): void
    {
        Log::debug('build event triggered by organisation observer');

        PublicWebsite\BuildEvent::dispatch();
        StaticWebsite\BuildEvent::dispatch();
    }
}
