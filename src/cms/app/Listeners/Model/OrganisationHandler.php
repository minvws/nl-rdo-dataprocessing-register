<?php

declare(strict_types=1);

namespace App\Listeners\Model;

use App\Events\Models\OrganisationEvent;
use App\Events\PublicWebsite\OrganisationPublishEvent;
use App\Events\PublicWebsite\OrganisationUnpublishEvent;
use App\Models\Organisation;

class OrganisationHandler
{
    public function handle(OrganisationEvent $event): void
    {
        $organisation = $event->organisation;

        $this->canBePublised($organisation)
            ? OrganisationPublishEvent::dispatch($organisation)
            : OrganisationUnpublishEvent::dispatch($organisation);
    }

    private function canBePublised(Organisation $organisation): bool
    {
        if ($organisation->trashed()) {
            return false;
        }

        if ($organisation->public_from === null) {
            return false;
        }

        return $organisation->public_from->isPast();
    }
}
