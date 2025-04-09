<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\Models\PublicWebsiteEvent;

class PublicWebsiteObserver
{
    public function updated(): void
    {
        PublicWebsiteEvent::dispatch();
    }
}
