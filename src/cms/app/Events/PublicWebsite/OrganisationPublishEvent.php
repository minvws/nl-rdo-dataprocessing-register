<?php

declare(strict_types=1);

namespace App\Events\PublicWebsite;

use App\Events\LogDispatchable;
use App\Models\Organisation;

class OrganisationPublishEvent
{
    use LogDispatchable;

    public function __construct(public readonly Organisation $organisation)
    {
    }
}
