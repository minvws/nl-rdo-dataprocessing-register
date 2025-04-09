<?php

declare(strict_types=1);

namespace App\Events\Models;

use App\Events\LogDispatchable;
use App\Models\Organisation;

class OrganisationEvent
{
    use LogDispatchable;

    public function __construct(public readonly Organisation $organisation)
    {
    }
}
