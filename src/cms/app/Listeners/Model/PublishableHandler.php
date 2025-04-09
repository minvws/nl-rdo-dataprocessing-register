<?php

declare(strict_types=1);

namespace App\Listeners\Model;

use App\Events\Models\PublishableEvent;
use App\Events\PublicWebsite\PublishablePublishEvent;
use App\Events\PublicWebsite\PublishableUnpublishEvent;
use App\Models\Contracts\Publishable;

class PublishableHandler
{
    public function handle(PublishableEvent $event): void
    {
        $publishable = $event->publishable;

        $this->canBePublished($publishable)
            ? PublishablePublishEvent::dispatch($publishable)
            : PublishableUnpublishEvent::dispatch($publishable);
    }

    private function canBePublished(Publishable $publishable): bool
    {
        if ($publishable->isDeleted()) {
            return false;
        }

        if ($publishable->getPublicFrom() === null) {
            return false;
        }

        return $publishable->getPublicFrom()->isPast();
    }
}
