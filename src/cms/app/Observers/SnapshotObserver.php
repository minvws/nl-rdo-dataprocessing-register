<?php

declare(strict_types=1);

namespace App\Observers;

use App\Config\Config;
use App\Events\PublicWebsite;
use App\Events\StaticWebsite;
use App\Models\Contracts\Reviewable;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\Obsolete;
use App\ValueObjects\CalendarDate;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Log;

class SnapshotObserver
{
    public function created(Snapshot $snapshot): void
    {
        $this->setReviewAt($snapshot);
    }

    public function updated(Snapshot $snapshot): void
    {
        $originalState = $snapshot->getOriginal('state');
        $currentState = $snapshot->state;

        if ($currentState instanceof Established) {
            if ($originalState instanceof Approved) {
                $this->dispatchBuildEvent();
            }

            $this->setReviewAt($snapshot);
        }

        if ($originalState instanceof Established && $currentState instanceof Obsolete) {
            $this->dispatchBuildEvent();
        }
    }

    private function dispatchBuildEvent(): void
    {
        Log::debug('build event triggered by snapshot observer');

        PublicWebsite\BuildEvent::dispatch();
        StaticWebsite\BuildEvent::dispatch();
    }

    private function setReviewAt(Snapshot $snapshot): void
    {
        if (!$snapshot->state instanceof Established) {
            return;
        }

        $snapshotSource = $snapshot->snapshotSource;
        if (!$snapshotSource instanceof Reviewable) {
            return;
        }

        if ($snapshotSource->getReviewAt() !== null) {
            return;
        }

        $reviewAt = CalendarDate::instance(CarbonImmutable::now(Config::string('app.display_timezone')))
            ->addMonths($snapshot->organisation->review_at_default_in_months);

        $snapshotSource->setReviewAt($reviewAt);
        $snapshotSource->save();
    }
}
