<?php

declare(strict_types=1);

namespace App\Events\Models;

use App\Events\LogDispatchable;
use App\Models\Contracts\Publishable;

class PublishableEvent
{
    use LogDispatchable;

    public function __construct(public readonly Publishable $publishable)
    {
    }
}
