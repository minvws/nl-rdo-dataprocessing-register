<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\PublicWebsite;
use App\Events\StaticWebsite;
use Illuminate\Support\Facades\Log;

class PublicWebsiteObserver
{
    public function created(): void
    {
        $this->dispatchBuildEvent();
    }

    public function updated(): void
    {
        $this->dispatchBuildEvent();
    }

    private function dispatchBuildEvent(): void
    {
        Log::debug('build event triggered by public website observer');

        PublicWebsite\BuildEvent::dispatch();
        StaticWebsite\BuildEvent::dispatch();
    }
}
