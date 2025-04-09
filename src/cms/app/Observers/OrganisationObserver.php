<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\Models\OrganisationEvent;
use App\Models\Organisation;

class OrganisationObserver
{
    public function created(Organisation $organisation): void
    {
        OrganisationEvent::dispatch($organisation);
    }

    public function updated(Organisation $organisation): void
    {
        OrganisationEvent::dispatch($organisation);
    }

    public function deleted(Organisation $organisation): void
    {
        OrganisationEvent::dispatch($organisation);
    }
}
