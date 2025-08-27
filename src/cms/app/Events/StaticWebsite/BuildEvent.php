<?php

declare(strict_types=1);

namespace App\Events\StaticWebsite;

use App\Events\LogDispatchable;

class BuildEvent
{
    use LogDispatchable;

    public function __construct()
    {
    }
}
