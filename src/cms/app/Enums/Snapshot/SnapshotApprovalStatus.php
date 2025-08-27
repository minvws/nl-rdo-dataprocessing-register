<?php

declare(strict_types=1);

namespace App\Enums\Snapshot;

enum SnapshotApprovalStatus: string
{
    case APPROVED = 'approved';
    case DECLINED = 'declined';
    case UNKNOWN = 'unknown';

    /**
     * @return array<SnapshotApprovalStatus>
     */
    public static function signed(): array
    {
        return [
            self::APPROVED,
            self::DECLINED,
        ];
    }

    /**
     * @return array<SnapshotApprovalStatus>
     */
    public static function unsigned(): array
    {
        return [
            self::UNKNOWN,
        ];
    }
}
