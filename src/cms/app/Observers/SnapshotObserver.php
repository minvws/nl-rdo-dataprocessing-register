<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\Models\PublishableEvent;
use App\Events\Models\SnapshotEvent;
use App\Models\Contracts\Publishable;
use App\Models\Snapshot;

class SnapshotObserver
{
    public function created(): void
    {
        SnapshotEvent::dispatch();
    }

    public function updated(Snapshot $snapshot): void
    {
        if ($snapshot->snapshotSource instanceof Publishable) {
            PublishableEvent::dispatch($snapshot->snapshotSource);
        }
    }
}
