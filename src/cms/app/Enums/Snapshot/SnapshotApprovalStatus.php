<?php

declare(strict_types=1);

namespace App\Enums\Snapshot;

enum SnapshotApprovalStatus: string
{
    case APPROVED = 'approved';
    case DECLINED = 'declined';
    case UNKNOWN = 'unknown';
}
