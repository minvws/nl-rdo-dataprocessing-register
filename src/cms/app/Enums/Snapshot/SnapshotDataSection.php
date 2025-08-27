<?php

declare(strict_types=1);

namespace App\Enums\Snapshot;

enum SnapshotDataSection: string
{
    case PUBLIC = 'public';
    case PRIVATE = 'private';
}
