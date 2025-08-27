<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Contracts\Publishable;

class PublishableObserver
{
    public function updated(Publishable $publishable): void
    {
        $publishable->observeUpdated();
    }

    public function deleted(Publishable $publishable): void
    {
        $publishable->observeDeleted();
    }
}
