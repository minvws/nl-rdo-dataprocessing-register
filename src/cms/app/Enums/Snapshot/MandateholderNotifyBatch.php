<?php

declare(strict_types=1);

namespace App\Enums\Snapshot;

enum MandateholderNotifyBatch: string
{
    case NONE = 'none';
    case WEEKLY = 'weekly';
}
