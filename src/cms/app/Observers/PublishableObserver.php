<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\Models\PublishableEvent;
use App\Models\Contracts\Publishable;

class PublishableObserver
{
    public function created(Publishable $publishable): void
    {
        PublishableEvent::dispatch($publishable);
    }

    public function updated(Publishable $publishable): void
    {
        PublishableEvent::dispatch($publishable);
    }

    public function deleted(Publishable $publishable): void
    {
        PublishableEvent::dispatch($publishable);
    }
}
