<?php

declare(strict_types=1);

namespace App\Enums\Snapshot;

enum MandateholderNotifyDirectly: string
{
    case NONE = 'none';
    case SINGLE = 'single';
    case BATCH = 'batch';
}
