<?php

declare(strict_types=1);

namespace App\Events\PublicWebsite;

use App\Events\LogDispatchable;
use App\Models\Contracts\Publishable;

class PublishableUnpublishEvent
{
    use LogDispatchable;

    public function __construct(public readonly Publishable $publishable)
    {
    }
}
